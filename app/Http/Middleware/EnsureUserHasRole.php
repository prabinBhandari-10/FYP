<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $guard = $role === 'admin' ? 'admin' : 'web';
        $user = Auth::guard($guard)->user();

        if (! $user || $user->role !== $role) {
            if ($role === 'admin' && Auth::guard('web')->check()) {
                return redirect()->route('dashboard');
            }

            if ($role === 'user' && Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('login');
        }

        Auth::shouldUse($guard);

        return $next($request);
    }
}
