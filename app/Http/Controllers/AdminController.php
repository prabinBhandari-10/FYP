<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'totalUsers' => 0,
            'totalReports' => 0,
            'openReports' => 0,
            'pendingClaims' => 0,
            'approvedClaims' => 0,
            'heldClaims' => 0,
        ];

        $recentReports = collect();
        $recentClaims = collect();

        if (Schema::hasTable('users')) {
            $stats['totalUsers'] = User::count();
        }

        if (Schema::hasTable('reports')) {
            $stats['totalReports'] = Report::count();

            if (Schema::hasColumn('reports', 'status')) {
                $stats['openReports'] = Report::where('status', 'open')->count();
            }

            $recentReports = Report::query()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        if (Schema::hasTable('claims')) {
            if (Schema::hasColumn('claims', 'status')) {
                $stats['pendingClaims'] = Claim::where('status', 'pending')->count();
                $stats['approvedClaims'] = Claim::where('status', 'approved')->count();
            }

            if (Schema::hasColumn('claims', 'held_at')) {
                $stats['heldClaims'] = Claim::whereNotNull('held_at')->where('status', 'pending')->count();
            }

            $recentClaims = Claim::query()
                ->with(['user', 'report'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentReports', 'recentClaims'));
    }

    public function claimsIndex(): View
    {
        $claims = Claim::query()
            ->with(['report.user', 'user'])
            ->latest()
            ->paginate(12);

        $rejectedCounts = Claim::query()
            ->where('status', 'rejected')
            ->select('user_id', DB::raw('COUNT(*) as rejected_count'))
            ->groupBy('user_id')
            ->pluck('rejected_count', 'user_id');

        return view('admin.claims.index', [
            'claims' => $claims,
            'rejectedCounts' => $rejectedCounts,
        ]);
    }

    public function approve(Claim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update([
            'status' => 'approved',
            'held_at' => null,
        ]);

        return back()->with('success', 'Claim approved.');
    }

    public function reject(Claim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update([
            'status' => 'rejected',
            'held_at' => null,
        ]);

        return back()->with('success', 'Claim rejected.');
    }

    public function hold(Claim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Only pending claims can be put on hold.');
        }

        $claim->update([
            'held_at' => now(),
        ]);

        return back()->with('success', 'Claim moved to hold.');
    }

    public function blockUser(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['admin' => 'You cannot block an admin user.']);
        }

        $user->update(['is_blocked' => true]);

        return back()->with('success', 'User has been blocked.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['admin' => 'You cannot delete an admin user.']);
        }

        $fakeClaimsCount = Claim::query()
            ->where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        if ($fakeClaimsCount < 3) {
            return back()->withErrors([
                'admin' => 'User cannot be deleted until they have at least 3 rejected (fake) claims.',
            ]);
        }

        $user->delete();

        return back()->with('success', 'User deleted for repeated fake claims.');
    }
}
