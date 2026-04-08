<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        if (! $user || $user->role !== 'admin') {
            if (Auth::guard('web')->check()) {
                return redirect()->route('dashboard')
                    ->with('error', 'You are not authorized to access admin pages.');
            }

            return redirect()->route('login');
        }

        Auth::shouldUse('admin');

        return $next($request);
    }
}
