<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'user',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome! Your account has been created.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($validated);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->user()?->is_blocked) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account has been blocked. Please contact the administrator.',
            ]);
        }

        return redirect()->intended($this->redirectPathForRole($request->user()));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function userDashboard(Request $request)
    {
        $user = $request->user();

        if ($user?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $userId = $user?->id;

        $stats = [
            'totalReports' => 0,
            'lostReports' => 0,
            'foundReports' => 0,
            'activeClaims' => 0,
        ];

        if (Schema::hasTable('reports')) {
            $stats['totalReports'] = Report::where('user_id', $userId)->count();
            $stats['lostReports'] = Report::where('user_id', $userId)->where('type', 'lost')->count();
            $stats['foundReports'] = Report::where('user_id', $userId)->where('type', 'found')->count();
        }

        if (Schema::hasTable('claims')) {
            $claimsQuery = DB::table('claims');

            if (Schema::hasColumn('claims', 'user_id')) {
                $claimsQuery->where('user_id', $userId);
            }

            if (Schema::hasColumn('claims', 'is_active')) {
                $claimsQuery->where('is_active', true);
            } elseif (Schema::hasColumn('claims', 'status')) {
                $claimsQuery->whereIn('status', ['active', 'pending', 'in_progress']);
            } elseif (Schema::hasColumn('claims', 'state')) {
                $claimsQuery->whereIn('state', ['active', 'pending', 'in_progress']);
            }

            $stats['activeClaims'] = $claimsQuery->count();
        }

        return view('dashboard', compact('stats'));
    }

    public function adminDashboard()
    {
        $stats = [
            'totalUsers' => 0,
            'totalReports' => 0,
            'openReports' => 0,
            'pendingClaims' => 0,
            'approvedClaims' => 0,
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
                $stats['pendingClaims'] = DB::table('claims')->where('status', 'pending')->count();
                $stats['approvedClaims'] = DB::table('claims')->where('status', 'approved')->count();
            }

            $recentClaims = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->leftJoin('reports', 'reports.id', '=', 'claims.item_id')
                ->select('claims.id', 'claims.status', 'claims.created_at', 'users.name as claimant_name', 'reports.title as report_title')
                ->orderByDesc('claims.created_at')
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentReports', 'recentClaims'));
    }

    protected function redirectPathForRole(User $user): string
    {
        return $user->role === 'admin'
            ? route('admin.dashboard')
            : route('dashboard');
    }
}