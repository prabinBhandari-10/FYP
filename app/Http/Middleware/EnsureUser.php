<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if (! $user || $user->role !== 'user') {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'This page is only available for normal users.');
            }

            return redirect()->route('login');
        }

        Auth::shouldUse('web');

        return $next($request);
    }
}
