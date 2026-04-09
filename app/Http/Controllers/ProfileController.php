<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('web')->user();

        $myReports = $user
            ->reports()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $myClaims = $user
            ->claims()
            ->with('report')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $myContactMessages = ContactMessage::query()
            ->where('email', $user->email)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $recentActivity = [
            'total_reports' => $user->reports()->count(),
            'total_claims' => $user->claims()->count(),
            'pending_claims' => $user->claims()->where('status', 'pending')->count(),
            'approved_claims' => $user->claims()->where('status', 'approved')->count(),
            'rejected_claims' => $user->claims()->where('status', 'rejected')->count(),
        ];

        return view('user.profile', compact('user', 'myReports', 'myClaims', 'myContactMessages', 'recentActivity'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password you entered is incorrect.',
            ])->errorBag('changePassword');
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The password confirmation does not match.',
            ])->errorBag('deleteAccount');
        }

        $userId = $user->id;

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('success', 'Your account has been deleted successfully.');
    }
}
