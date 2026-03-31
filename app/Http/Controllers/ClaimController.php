<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $claims = Claim::query()
            ->with('report')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('claims.index', [
            'claims' => $claims,
        ]);
    }

    public function adminIndex()
    {
        $claims = Claim::query()
            ->with(['report', 'user'])
            ->latest()
            ->paginate(12);

        return view('admin.claims.index', [
            'claims' => $claims,
        ]);
    }

    public function store(Request $request, Report $report)
    {
        $existingClaim = Claim::query()
            ->where('user_id', $request->user()->id)
            ->where('item_id', $report->id)
            ->exists();

        if ($existingClaim) {
            return back()
                ->withErrors(['claim' => 'You already submitted a claim for this item.'])
                ->withInput();
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'proof_text' => ['nullable', 'string', 'max:2000'],
        ]);

        Claim::create([
            'user_id' => $request->user()->id,
            'item_id' => $report->id,
            'message' => $validated['message'],
            'proof_text' => $validated['proof_text'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('claims.index')
            ->with('success', 'Claim submitted successfully.');
    }

    public function approve(Claim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update(['status' => 'approved']);

        return back()->with('success', 'Claim approved.');
    }

    public function reject(Claim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update(['status' => 'rejected']);

        return back()->with('success', 'Claim rejected.');
    }
}
