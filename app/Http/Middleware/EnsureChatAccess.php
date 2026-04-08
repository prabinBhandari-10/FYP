<?php

namespace App\Http\Middleware;

use App\Models\Claim;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureChatAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $claim = $request->route('claim');

        if (! $claim instanceof Claim) {
            abort(404);
        }

        $webUser = Auth::guard('web')->user();
        $adminUser = Auth::guard('admin')->user();

        if (! $webUser && ! $adminUser) {
            return redirect()->route('login');
        }

        if ($claim->status !== 'approved') {
            abort(403, 'Chat is only available after admin approval.');
        }

        $report = $claim->report()->select('id', 'user_id')->first();

        if (! $report) {
            abort(404);
        }

        $allowedIds = [$claim->user_id, $report->user_id];

        $user = null;

        if ($webUser && in_array($webUser->id, $allowedIds, true)) {
            $user = $webUser;
            Auth::shouldUse('web');
        } elseif ($adminUser && in_array($adminUser->id, $allowedIds, true)) {
            $user = $adminUser;
            Auth::shouldUse('admin');
        }

        if (! $user) {
            abort(403);
        }

        return $next($request);
    }
}