<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureChatAccess;
use App\Http\Middleware\EnsureUser;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('home'));

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'admin' => EnsureAdmin::class,
            'user' => EnsureUser::class,
            'chat' => EnsureChatAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired. Please refresh and try again.',
                ], 419);
            }

            return redirect()
                ->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Your session has expired. Please try again.');
        });
    })->create();
