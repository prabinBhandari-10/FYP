<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ItemReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->string('q') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        $reports = $query
            ->orderByDesc('date')
            ->paginate(9)
            ->withQueryString();

        $categories = Report::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('reports.index', [
            'reports' => $reports,
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, Report $report)
    {
        $report->load('user');
        $existingClaim = $report->claims()
            ->where('user_id', $request->user()->id)
            ->first();

        return view('reports.show', [
            'report' => $report,
            'existingClaim' => $existingClaim,
        ]);
    }
    public function createLost()
    {
        return view('reports.create', [
            'type' => 'lost',
            'title' => 'Report Lost Item',
            'submitRoute' => route('reports.lost.store'),
        ]);
    }

    public function createFound()
    {
        return view('reports.create', [
            'type' => 'found',
            'title' => 'Report Found Item',
            'submitRoute' => route('reports.found.store'),
        ]);
    }

    public function storeLost(Request $request)
    {
        return $this->storeReport($request, 'lost');
    }

    public function storeFound(Request $request)
    {
        return $this->storeReport($request, 'found');
    }

    protected function storeReport(Request $request, string $type)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        Report::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $type,
            'category' => $validated['category'],
            'location' => $validated['location'],
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => 'open',
        ]);

        return redirect()->route('dashboard')->with('success', 'Item report submitted successfully.');
    }
}
