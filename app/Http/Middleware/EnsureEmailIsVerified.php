<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // If no user is logged in, proceed (auth middleware will handle it)
        if (!$user) {
            return $next($request);
        }
        
        // Allow users who:
        // 1. Have verified their email
        // 2. Are existing users (don't have a verification code since they were backfilled)
        if ($user->email_verified_at || !$user->verification_code) {
            return $next($request);
        }

        return redirect()->route('verify-email')->with('error', 'Please verify your email first.');
    }
}

