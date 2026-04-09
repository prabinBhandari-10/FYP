<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationCodeMail;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'email' => [
                'required', 
                'string', 
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/', 
                'max:255', 
                'unique:users,email'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.regex' => 'Please enter a valid email address',
            'email.unique' => 'Email already registered',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'user',
            'password' => Hash::make($validated['password']),
        ]);

        // Generate verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $verificationCode));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        $request->session()->put('pending_verification_user_id', $user->id);

        return redirect()->route('verify-email')->with('success', 'Account created! Please check your email for the verification code.');
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

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        if ($user->is_blocked) {
            return back()->withErrors([
                'email' => 'Your account has been blocked. Please contact the administrator.',
            ]);
        }

        $guard = $user->role === 'admin' ? 'admin' : 'web';

        Auth::guard($guard)->login($user, $request->boolean('remember'));
        Auth::shouldUse($guard);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathForRole($user))
            ->with('success', 'Login successful.');
    }

    public function logout(Request $request)
    {
        $guard = $request->routeIs('admin.logout') ? 'admin' : 'web';

        Auth::guard($guard)->logout();
        Auth::shouldUse($guard);

        $request->session()->regenerate();
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

        return view('user.profile', compact('stats'));
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
            ? route('admin.home')
            : route('home');
    }

    public function showVerifyEmailForm()
    {
        $userId = session('pending_verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')->with('error', 'Please register first.');
        }

        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $userId = session('pending_verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        $validated = $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'verification_code.required' => 'Please enter the verification code.',
            'verification_code.size' => 'Verification code must be 6 digits.',
        ]);

        // Check if code is expired
        if ($user->verification_code_expires_at && now()->isAfter($user->verification_code_expires_at)) {
            return back()->withErrors(['verification_code' => 'Verification code has expired. Please request a new one.']);
        }

        // Check if code matches
        if ($validated['verification_code'] !== $user->verification_code) {
            return back()->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        event(new Registered($user));

        // Log user in
        Auth::guard('web')->login($user);
        Auth::shouldUse('web');
        $request->session()->regenerate();
        $request->session()->forget('pending_verification_user_id');

        return redirect()->route('profile')->with('success', 'Email verified successfully! Welcome to Lost & Found Management System.');
    }

    public function resendVerificationCode(Request $request)
    {
        $userId = session('pending_verification_user_id');
        
        if (!$userId) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        // Generate new verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $verificationCode));
            return back()->with('success', 'New verification code has been sent to your email.');
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }
}