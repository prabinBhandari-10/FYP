<?php

namespace App\Http\Controllers;

use App\Events\ReportSubmitted;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemReportController extends Controller
{
    public function trackForm(Request $request)
    {
        $uid = strtoupper(trim((string) $request->string('uid')));

        if ($uid !== '') {
            return redirect()->route('reports.track.show', ['reportUid' => $uid]);
        }

        return view('reports.track');
    }

    public function trackShow(string $reportUid)
    {
        $uid = strtoupper(trim($reportUid));

        $report = Report::query()
            ->with('user')
            ->where('report_uid', $uid)
            ->first();

        if (! $report) {
            return redirect()
                ->route('reports.track.form')
                ->withErrors(['uid' => 'No report found for the provided UID.']);
        }

        $statusMessage = match ($report->status) {
            'pending' => 'Your report is waiting for admin approval and is not visible publicly yet.',
            'open' => 'Your report is approved and currently visible to users for matching and claims.',
            'closed' => 'Your report has been closed by admin and is not publicly active.',
            default => 'Your report status has been updated.',
        };

        return view('reports.track', [
            'report' => $report,
            'statusMessage' => $statusMessage,
        ]);
    }

    public function index(Request $request)
    {
        $query = Report::query()->where('status', 'open');

        if ($request->filled('q')) {
            $term = trim((string) $request->string('q'));

            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                    ->orWhere('category', 'like', '%' . $term . '%')
                    ->orWhere('location', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', (string) $request->string('category'));
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->string('type'));
        }

        $reports = $query
            ->orderByDesc('date')
            ->paginate(9)
            ->withQueryString();

        $categories = Report::query()
            ->where('status', 'open')
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
        $user = $request->user();
        $canViewUnapproved = $user && ($user->role === 'admin' || $user->id === $report->user_id);

        if ($report->status !== 'open' && ! $canViewUnapproved) {
            abort(404);
        }

        $report->load('user');
        $existingClaim = null;
        $potentialMatches = $this->findPotentialMatches($report);

        if ($user) {
            $existingClaim = $report->claims()
                ->where('user_id', $user->id)
                ->first();
        }

        return view('reports.show', [
            'report' => $report,
            'existingClaim' => $existingClaim,
            'potentialMatches' => $potentialMatches,
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
            'Unknown' => [],
        ];

        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:255'],
            'reporter_email' => ['required', 'string', 'email', 'max:255'],
            'reporter_phone' => ['required', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'block' => ['required', 'string', 'in:Nepal Block,UK Block,Pokhara City,Pokhara,Unknown'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_note' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        if ($validated['block'] === 'Pokhara') {
            $validated['block'] = 'Pokhara City';
        }

        $selectedLocation = trim((string) ($validated['location'] ?? ''));
        $locationNote = trim((string) ($validated['location_note'] ?? ''));
        $validLocations = $locationByBlock[$validated['block']] ?? [];

        $resolvedLocation = null;

        if ($selectedLocation !== '' && in_array($selectedLocation, $validLocations, true)) {
            $resolvedLocation = $selectedLocation;
        } elseif ($locationNote !== '') {
            // Allow approximate user-provided location when exact place is unknown.
            $resolvedLocation = $locationNote;
        }

        if ($resolvedLocation === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'location' => 'Select an exact location or provide an approximate location note.',
                ]);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id' => $request->user()->id,
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'reporter_phone' => $validated['reporter_phone'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $type,
            'category' => $validated['category'],
            'location' => $validated['block'] . ' - ' . $resolvedLocation,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => $request->user()->role === 'admin' ? 'open' : 'pending',
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        // Handle multiple images if provided
        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('reports', 'public');
                $report->images()->create([
                    'image_path' => $imagePath,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        event(new ReportSubmitted($report, $request->user()));

        return redirect()
            ->route('items.show', $report)
            ->with('success', $report->status === 'pending'
                ? 'Item report submitted successfully. UID: ' . $report->report_uid . '. It is pending admin approval before public visibility.'
                : 'Item report submitted successfully. UID: ' . $report->report_uid . '. We found possible matches below.')
            ->with('show_matches', true);
    }

    public function storeSighting(Request $request, Report $report)
    {
        if ($report->status !== 'open') {
            abort(404);
        }

        if ($report->type !== 'lost') {
            abort(403, 'Sightings can only be reported for lost items.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'reporter_name' => ['nullable', 'string', 'max:255'],
            'reporter_email' => ['nullable', 'email', 'max:255'],
        ]);

        $report->sightings()->create([
            'user_id' => $request->user()?->id,
            'reporter_name' => $validated['reporter_name'] ?? $request->user()?->name,
            'reporter_email' => $validated['reporter_email'] ?? $request->user()?->email,
            'message' => $validated['message'],
            'location' => $validated['location'],
        ]);

        return back()->with('success', 'Thank you! Your sighting report has been sent to the item owner.');
    }

    protected function findPotentialMatches(Report $report, int $limit = 4): Collection
    {
        $targetType = $report->type === 'lost' ? 'found' : 'lost';
        $baseLocation = trim((string) Str::before((string) $report->location, ' - '));

        $candidates = Report::query()
            ->where('type', $targetType)
            ->where('status', 'open')
            ->whereKeyNot($report->id)
            ->where(function ($builder) use ($report, $baseLocation) {
                $builder->where('category', $report->category)
                    ->orWhere('title', 'like', '%' . $report->title . '%')
                    ->orWhere('description', 'like', '%' . $report->title . '%');

                if ($baseLocation !== '') {
                    $builder->orWhere('location', 'like', '%' . $baseLocation . '%');
                }
            })
            ->latest()
            ->limit(80)
            ->get();

        return $candidates
            ->map(function (Report $candidate) use ($report) {
                $score = $this->calculateMatchScore($report, $candidate);
                $hasStrongSignal = $this->hasStrongMatchSignal($report, $candidate);

                return [
                    'report' => $candidate,
                    'score' => $score,
                    'has_strong_signal' => $hasStrongSignal,
                ];
            })
            ->filter(fn (array $item) => $item['has_strong_signal'] && $item['score'] >= 45)
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    protected function calculateMatchScore(Report $source, Report $candidate): int
    {
        $score = 0;

        if (strcasecmp((string) $source->category, (string) $candidate->category) === 0) {
            $score += 20;
        }

        $titleA = $this->normalizeText((string) $source->title);
        $titleB = $this->normalizeText((string) $candidate->title);
        similar_text($titleA, $titleB, $titleSimilarityPercent);
        $score += (int) min(40, round($titleSimilarityPercent * 0.4));

        $sourceKeywords = $this->keywords((string) $source->description . ' ' . (string) $source->title);
        $candidateKeywords = $this->keywords((string) $candidate->description . ' ' . (string) $candidate->title);
        $sharedKeywords = count(array_intersect($sourceKeywords, $candidateKeywords));
        $score += min(20, $sharedKeywords * 2);

        $sourceLocationBlock = trim((string) Str::before((string) $source->location, ' - '));
        $candidateLocationBlock = trim((string) Str::before((string) $candidate->location, ' - '));
        if ($sourceLocationBlock !== '' && strcasecmp($sourceLocationBlock, $candidateLocationBlock) === 0) {
            $score += 10;
        }

        if ($source->date && $candidate->date) {
            $dayDiff = abs($source->date->diffInDays($candidate->date));
            if ($dayDiff <= 2) {
                $score += 8;
            } elseif ($dayDiff <= 7) {
                $score += 5;
            } elseif ($dayDiff <= 14) {
                $score += 3;
            }
        }

        if ($source->image && $candidate->image) {
            $score += 5;
        }

        return min(99, $score);
    }

    protected function hasStrongMatchSignal(Report $source, Report $candidate): bool
    {
        $titleA = $this->normalizeText((string) $source->title);
        $titleB = $this->normalizeText((string) $candidate->title);
        similar_text($titleA, $titleB, $titleSimilarityPercent);

        if ($titleSimilarityPercent >= 45) {
            return true;
        }

        $titleTokenOverlap = count(array_intersect(
            $this->keywords((string) $source->title),
            $this->keywords((string) $candidate->title)
        ));

        if ($titleTokenOverlap >= 1) {
            return true;
        }

        $descriptionTokenOverlap = count(array_intersect(
            $this->keywords((string) $source->description . ' ' . (string) $source->title),
            $this->keywords((string) $candidate->description . ' ' . (string) $candidate->title)
        ));

        return $descriptionTokenOverlap >= 3;
    }

    protected function normalizeText(string $value): string
    {
        return trim((string) preg_replace('/\s+/', ' ', Str::lower($value)));
    }

    protected function keywords(string $value): array
    {
        $stopWords = [
            'the', 'and', 'for', 'with', 'from', 'this', 'that', 'item', 'lost', 'found',
            'near', 'area', 'have', 'has', 'was', 'were', 'your', 'about', 'into', 'after',
            'before', 'around', 'very', 'just', 'then', 'than', 'into', 'onto', 'over', 'under', 'college',
        ];

        $normalized = $this->normalizeText($value);
        $parts = preg_split('/[^a-z0-9]+/', $normalized) ?: [];

        $tokens = array_filter($parts, function (string $token) use ($stopWords) {
            return strlen($token) >= 3 && ! in_array($token, $stopWords, true);
        });

        return array_values(array_unique($tokens));
    }
}
