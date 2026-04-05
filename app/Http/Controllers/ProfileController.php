<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $myReports = $user
            ->reports()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $myClaims = $user
            ->claims()
            ->with('report')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $recentActivity = [
            'total_reports' => $user->reports()->count(),
            'total_claims' => $user->claims()->count(),
            'pending_claims' => $user->claims()->where('status', 'pending')->count(),
            'approved_claims' => $user->claims()->where('status', 'approved')->count(),
            'rejected_claims' => $user->claims()->where('status', 'rejected')->count(),
        ];

        return view('user.profile', compact('user', 'myReports', 'myClaims', 'recentActivity'));
    }
}
