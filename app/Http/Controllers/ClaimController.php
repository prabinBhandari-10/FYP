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
            'citizenship_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'proof_text' => ['required_without:proof_photo', 'nullable', 'string', 'max:2000'],
            'proof_photo' => ['required_without:proof_text', 'nullable', 'file', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        $citizenshipPath = $request->file('citizenship_document')->store('claims/citizenship', 'public');
        $proofPhotoPath = null;
        if ($request->hasFile('proof_photo')) {
            $proofPhotoPath = $request->file('proof_photo')->store('claims/proof', 'public');
        }

        Claim::create([
            'user_id' => $request->user()->id,
            'item_id' => $report->id,
            'message' => $validated['message'],
            'citizenship_document_path' => $citizenshipPath,
            'proof_text' => $validated['proof_text'] ?? null,
            'proof_photo_path' => $proofPhotoPath,
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
