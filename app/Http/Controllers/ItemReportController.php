<?php

namespace App\Http\Controllers;

use App\Events\ReportSubmitted;
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
        $existingClaim = null;
        $user = $request->user();

        if ($user) {
            $existingClaim = $report->claims()
                ->where('user_id', $user->id)
                ->first();
        }

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
        $locationByBlock = [
            'Nepal Block' => [
                'Annapurna',
                'Machapuchhre',
                'Begnas',
                'Rupa',
                'Rara',
                'Tilicho',
                'Nilgiri',
                'Kapuche',
            ],
            'UK Block' => [
                'Basketball Court',
                'Library',
                'Canteen',
                'Parking Area',
                'Table Tennis Board',
            ],
            'Pokhara City' => [
                'Lakeside',
                'Mahendrapool',
                'Prithvi Chowk',
                'Chipledhunga',
                'New Road',
                'Bagar',
                'Bindhyabasini',
                'Phewa Lake',
                'Talchowk',
                'Miyapatan',
                'Batulechaur',
                'Hemja',
                'Srijanachowk',
                'Nayabazar',
                'Rambazar',
            ],
        ];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'block' => ['required', 'string', 'in:Nepal Block,UK Block,Pokhara City,Pokhara'],
            'location' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($validated['block'] === 'Pokhara') {
            $validated['block'] = 'Pokhara City';
        }

        if (! in_array($validated['location'], $locationByBlock[$validated['block']] ?? [], true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'location' => 'Please select a valid location for the selected block.',
                ]);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $type,
            'category' => $validated['category'],
            'location' => $validated['block'] . ' - ' . $validated['location'],
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => 'open',
        ]);

        event(new ReportSubmitted($report, $request->user()));

        return redirect()->route('dashboard')->with('success', 'Item report submitted successfully.');
    }
}
