<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ChatConversation;
use App\Models\Claim;
use App\Models\FoundResponse;
use App\Models\Notification;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function home(): View
    {
        $stats = $this->adminHomeStats();

        $latestReport = null;
        $latestClaim = null;
        $latestNotification = null;

        if (Schema::hasTable('reports')) {
            $latestReport = Report::query()->with('user')->latest()->first();
        }

        if (Schema::hasTable('claims')) {
            $latestClaim = Claim::query()->with(['user', 'report'])->latest()->first();
        }

        if (Schema::hasTable('notifications')) {
            $latestNotification = Notification::query()->with(['user', 'report', 'claim'])->latest()->first();
        }

        $urgentReports = collect();
        if (Schema::hasTable('reports')) {
            $urgentReports = Report::query()
                ->where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(3))
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        $pendingClaims = collect();
        if (Schema::hasTable('claims')) {
            $pendingClaims = Claim::query()
                ->where('status', 'pending')
                ->with(['user', 'report'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.home', compact(
            'stats',
            'latestReport',
            'latestClaim',
            'latestNotification',
            'urgentReports',
            'pendingClaims'
        ));
    }

    public function dashboard(): View
    {
        $stats = $this->adminDashboardStats();

        $recentReports = collect();
        $recentClaims = collect();
        $recentUsers = collect();
        $dailyReportTrend = [];
        $topCategories = collect();
        $topLocations = collect();
        $reportTypeBreakdown = collect();
        $claimStatusBreakdown = collect();

        if (Schema::hasTable('reports')) {
            $recentReports = Report::query()
                ->with('user')
                ->where('status', '!=', 'deleted')
                ->latest()
                ->take(8)
                ->get();

            $topCategories = Report::query()
                ->where('status', '!=', 'deleted')
                ->select('category', DB::raw('COUNT(*) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            $topLocations = Report::query()
                ->where('status', '!=', 'deleted')
                ->select('location', DB::raw('COUNT(*) as total'))
                ->groupBy('location')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            $reportTypeBreakdown = Report::query()
                ->where('status', '!=', 'deleted')
                ->select('type', DB::raw('COUNT(*) as total'))
                ->groupBy('type')
                ->get();

            $dailyReportTrend = $this->buildDailyTrend(Report::class);
        }

        if (Schema::hasTable('claims')) {
            $recentClaims = Claim::query()
                ->with(['user', 'report'])
                ->latest()
                ->take(8)
                ->get();

            $claimStatusBreakdown = Claim::query()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get();
        }

        if (Schema::hasTable('users')) {
            $recentUsers = User::query()->latest()->take(5)->get();
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentReports',
            'recentClaims',
            'recentUsers',
            'dailyReportTrend',
            'topCategories',
            'topLocations',
            'reportTypeBreakdown',
            'claimStatusBreakdown'
        ));
    }

    public function notificationsIndex(): View
    {
        $notifications = Notification::query()
            ->with(['user', 'report', 'claim'])
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::query()->where('is_read', false)->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function paymentsIndex(): View
    {
        $pendingPayments = 0;
        $recentNotes = collect();

        if (Schema::hasTable('notifications')) {
            $recentNotes = Notification::query()->latest()->take(5)->get();
        }

        return view('admin.payments.index', [
            'pendingPayments' => $pendingPayments,
            'recentNotes' => $recentNotes,
        ]);
    }

    public function claimsIndex(): View
    {
        $claims = Claim::query()
            ->with(['report.user', 'user'])
            ->latest()
            ->paginate(12);

        $rejectedCounts = Claim::query()
            ->where('status', 'rejected')
            ->select('user_id', DB::raw('COUNT(*) as rejected_count'))
            ->groupBy('user_id')
            ->pluck('rejected_count', 'user_id');

        return view('admin.claims.index', [
            'claims' => $claims,
            'rejectedCounts' => $rejectedCounts,
        ]);
    }

    public function usersIndex(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('q')) {
            $term = (string) $request->string('q');
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', (string) $request->string('role'));
        }

        if ($request->filled('blocked')) {
            $isBlocked = (string) $request->string('blocked') === 'yes';
            $query->where('is_blocked', $isBlocked);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => [
                'q' => (string) $request->string('q'),
                'role' => (string) $request->string('role'),
                'blocked' => (string) $request->string('blocked'),
            ],
        ]);
    }

    public function usersShow(User $user): View
    {
        $user->load([
            'reports' => function ($query) {
                $query->latest();
            },
            'claims.report',
        ]);

        $stats = [
            'totalReports' => $user->reports->count(),
            'lostReports' => $user->reports->where('type', 'lost')->count(),
            'foundReports' => $user->reports->where('type', 'found')->count(),
            'totalClaims' => $user->claims->count(),
            'pendingClaims' => $user->claims->where('status', 'pending')->count(),
            'rejectedClaims' => $user->claims->where('status', 'rejected')->count(),
        ];

        return view('admin.users.show', [
            'managedUser' => $user,
            'stats' => $stats,
        ]);
    }

    public function reportsIndex(Request $request): View
    {
        $query = Report::query()
            ->with('user')
            ->where('status', '!=', 'deleted')
            ->latest();

        $type = (string) $request->string('type');
        if (in_array($type, ['lost', 'found'], true)) {
            $query->where('type', $type);
        }

        if ($request->filled('q')) {
            $query->where(function ($builder) use ($request) {
                $term = (string) $request->string('q');

                $builder->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                    ->orWhere('reporter_name', 'like', '%' . $term . '%')
                    ->orWhere('reporter_email', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        $urgency = (string) $request->string('urgency');
        if (in_array($urgency, ['normal', 'urgent'], true)) {
            $query->where('urgency', $urgency);
        }

        $reports = $query->paginate(12)->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => [
                'q' => (string) $request->string('q'),
                'type' => $type,
                'status' => (string) $request->string('status'),
                'urgency' => $urgency,
            ],
        ]);
    }

    public function reportsCreate(string $type = 'lost'): View
    {
        abort_unless(in_array($type, ['lost', 'found'], true), 404);

        return view('admin.reports.form', [
            'report' => new Report([
                'type' => $type,
                'status' => 'open',
                'date' => now()->toDateString(),
                'block' => 'Nepal Block',
            ]),
            'formTitle' => 'Create ' . ucfirst($type) . ' Report',
            'formAction' => route('admin.reports.store'),
            'submitLabel' => 'Create Report',
            'isEdit' => false,
        ]);
    }

    public function reportsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->reportRules(), [
            'reporter_phone.regex' => 'Phone number must be exactly 10 digits',
        ]);

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
                'Canteen',
                'Library',
                'Parking Area',
                'Basketball Court',
                'Table Tennis Board',
            ],
            'UK Block' => [
                'Parking Area',
                'Table Tennis Board',
                'Open Access Lab',
                'Stonehenge',
                'Big Ben',
                'kingstone'
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
            'color' => $validated['color'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'location' => $validated['block'] . ' - ' . $resolvedLocation,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => 'open',
            'urgency' => $validated['urgency'] ?? 'normal',
            'payment_status' => $validated['urgency'] === 'urgent' ? 'completed' : 'completed',
        ]);

        $this->logAdminAction(
            $request,
            'report_created',
            $report,
            ucfirst($report->type) . ' report created by admin.',
            ['new' => $report->only(['type', 'title', 'category', 'location', 'status'])]
        );

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', ucfirst($report->type) . ' report created successfully.');
    }

    public function reportsShow(Report $report): View
    {
        $report->load(['user', 'claims.user', 'sightings.user', 'foundResponses.user', 'foundResponses.reviewer']);

        return view('admin.reports.show', [
            'report' => $report,
        ]);
    }

    public function reportsEdit(Report $report): View
    {
        // Parse the location to extract block if not already set
        if (!$report->block && $report->location) {
            $parts = explode(' - ', $report->location, 2);
            $report->block = $parts[0] ?? 'Nepal Block';
            $report->location = $parts[1] ?? '';
        }

        return view('admin.reports.form', [
            'report' => $report,
            'formTitle' => 'Edit ' . ucfirst($report->type) . ' Report',
            'formAction' => route('admin.reports.update', $report),
            'submitLabel' => 'Update Report',
            'isEdit' => true,
        ]);
    }

    public function reportsUpdate(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate($this->reportRules(), [
            'reporter_phone.regex' => 'Phone number must be exactly 10 digits',
        ]);

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
                'Canteen',
                'Library',
                'Parking Area',
                'Basketball Court',
                'Table Tennis Board',
            ],
            'UK Block' => [
                'Parking Area',
                'Table Tennis Board',
                'Open Access Lab',
                'Stonehenge',
                'Big Ben',
                'kingstone'
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
            $resolvedLocation = $locationNote;
        }

        if ($resolvedLocation === null) {
            return back()
                ->withInput()
                ->withErrors([
                    'location' => 'Select an exact location or provide an approximate location note.',
                ]);
        }

        $before = $report->only([
            'type',
            'reporter_name',
            'reporter_email',
            'reporter_phone',
            'title',
            'description',
            'category',
            'location',
            'latitude',
            'longitude',
            'date',
            'status',
        ]);

        $imagePath = $report->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');

            if ($report->image) {
                Storage::disk('public')->delete($report->image);
            }
        }

        $report->update([
            'type' => $validated['type'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'reporter_phone' => $validated['reporter_phone'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'category' => $validated['category'],
            'location' => $validated['block'] . ' - ' . $resolvedLocation,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'urgency' => $validated['urgency'] ?? 'normal',
            'payment_status' => $validated['payment_status'] ?? ($validated['urgency'] === 'urgent' ? 'pending' : 'completed'),
        ]);

        $this->logAdminAction(
            $request,
            'report_updated',
            $report,
            ucfirst($report->type) . ' report updated by admin.',
            [
                'old' => $before,
                'new' => $report->only([
                    'type',
                    'reporter_name',
                    'reporter_email',
                    'reporter_phone',
                    'title',
                    'description',
                    'color',
                    'category',
                    'location',
                    'latitude',
                    'longitude',
                    'date',
                    'status',
                ]),
            ]
        );

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', ucfirst($report->type) . ' report updated successfully.');
    }

    public function reportsDestroy(Request $request, Report $report): RedirectResponse
    {
        $snapshot = $report->only([
            'type',
            'title',
            'category',
            'location',
            'status',
            'reporter_name',
            'reporter_email',
            'reporter_phone',
        ]);

        // Mark report as deleted instead of permanently deleting
        $report->update(['status' => 'deleted']);

        $this->logAdminAction(
            $request,
            'report_deleted',
            null,
            ucfirst((string) $snapshot['type']) . ' report deleted by admin.',
            ['deleted' => $snapshot]
        );

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function reportsApprove(Request $request, Report $report): RedirectResponse
    {
        if ($report->status === 'open') {
            return back()->with('success', 'Report is already approved and visible.');
        }

        $beforeStatus = $report->status;
        $report->update(['status' => 'open']);

        // Create notification record for user
        if ($report->user_id) {
            Notification::create([
                'user_id' => $report->user_id,
                'type' => 'report_approved',
                'title' => 'Report Approved',
                'message' => "Your {$report->type} report '{$report->title}' has been approved and is now visible to all users.",
                'related_report_id' => $report->id,
                'is_read' => false,
                'is_email_sent' => false,
            ]);

            // Send email notification
            try {
                $report->user->notify(new \App\Notifications\ReportApprovedNotification($report));
            } catch (\Exception $e) {
                \Log::error('Failed to send report approval email: ' . $e->getMessage());
            }
        }

        $this->logAdminAction(
            $request,
            'report_approved',
            $report,
            ucfirst($report->type) . ' report approved by admin.',
            ['old_status' => $beforeStatus, 'new_status' => 'open']
        );

        return back()->with('success', 'Report approved and now visible publicly.');
    }

    public function reportsReject(Request $request, Report $report): RedirectResponse
    {
        if ($report->status === 'closed') {
            return back()->with('success', 'Report is already rejected/closed.');
        }

        $beforeStatus = $report->status;
        $report->update(['status' => 'closed']);

        // Create notification record for user
        if ($report->user_id) {
            Notification::create([
                'user_id' => $report->user_id,
                'type' => 'report_rejected',
                'title' => 'Report Rejected',
                'message' => "Your {$report->type} report '{$report->title}' has been rejected and is not visible to users.",
                'related_report_id' => $report->id,
                'is_read' => false,
                'is_email_sent' => false,
            ]);

            // Send email notification
            try {
                $report->user->notify(new \App\Notifications\ReportRejectedNotification($report));
            } catch (\Exception $e) {
                \Log::error('Failed to send report rejection email: ' . $e->getMessage());
            }
        }

        $this->logAdminAction(
            $request,
            'report_rejected',
            $report,
            ucfirst($report->type) . ' report rejected by admin.',
            ['old_status' => $beforeStatus, 'new_status' => 'closed']
        );

        return back()->with('success', 'Report rejected and kept hidden from public listings.');
    }

    public function foundResponsesApprove(Request $request, FoundResponse $foundResponse): RedirectResponse
    {
        if ($foundResponse->status !== 'pending') {
            return back()->with('success', 'This found response is already reviewed.');
        }

        $foundResponse->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $lostReport = $foundResponse->report;

        Report::create([
            'user_id' => $request->user()->id,
            'reporter_name' => $foundResponse->is_anonymous ? 'Anonymous Finder' : ($foundResponse->name ?: 'Finder'),
            'reporter_email' => $foundResponse->is_anonymous ? 'hidden@example.com' : ($foundResponse->contact ?: 'hidden@example.com'),
            'reporter_phone' => $foundResponse->is_anonymous ? 'Hidden' : ($foundResponse->contact ?: 'Not provided'),
            'title' => 'Possible match for ' . $lostReport->title,
            'description' => 'Created from admin-approved found response.\n\nFinder message: ' . $foundResponse->message,
            'color' => $lostReport->color ?? 'Unknown',
            'type' => 'found',
            'category' => $lostReport->category,
            'location' => $foundResponse->found_location ?: $lostReport->location,
            'date' => $foundResponse->found_date ?: now()->toDateString(),
            'image' => $foundResponse->image,
            'status' => 'open',
            'is_anonymous' => true,
        ]);

        $this->logAdminAction(
            $request,
            'found_response_approved',
            $foundResponse,
            'Found response approved by admin and published as a possible found report.',
            ['found_response_id' => $foundResponse->id, 'report_id' => $lostReport->id]
        );

        return back()->with('success', 'Found response approved and added as a possible match.');
    }

    public function foundResponsesReject(Request $request, FoundResponse $foundResponse): RedirectResponse
    {
        if ($foundResponse->status !== 'pending') {
            return back()->with('success', 'This found response is already reviewed.');
        }

        $foundResponse->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $this->logAdminAction(
            $request,
            'found_response_rejected',
            $foundResponse,
            'Found response rejected by admin.',
            ['found_response_id' => $foundResponse->id, 'report_id' => $foundResponse->report_id]
        );

        return back()->with('success', 'Found response rejected.');
    }

    public function reportsExportCsv(Request $request): StreamedResponse
    {
        $query = Report::query()->with('user')->latest();

        $type = (string) $request->string('type');
        if (in_array($type, ['lost', 'found'], true)) {
            $query->where('type', $type);
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        if ($request->filled('q')) {
            $query->where(function ($builder) use ($request) {
                $term = (string) $request->string('q');

                $builder->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                    ->orWhere('reporter_name', 'like', '%' . $term . '%')
                    ->orWhere('reporter_email', 'like', '%' . $term . '%');
            });
        }

        $filename = 'reports_export_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'wb');

            fputcsv($handle, [
                'ID',
                'Type',
                'Title',
                'Category',
                'Status',
                'Location',
                'Date',
                'Reporter Name',
                'Reporter Email',
                'Reporter Phone',
                'Submitted By User',
                'Submitted By Email',
                'Latitude',
                'Longitude',
                'Created At',
            ]);

            foreach ($query->cursor() as $report) {
                fputcsv($handle, [
                    $report->id,
                    $report->type,
                    $report->title,
                    $report->category,
                    $report->status,
                    $report->location,
                    optional($report->date)->format('Y-m-d'),
                    $report->reporter_name,
                    $report->reporter_email,
                    $report->reporter_phone,
                    $report->user?->name,
                    $report->user?->email,
                    $report->latitude,
                    $report->longitude,
                    optional($report->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function auditLogsIndex(): View
    {
        $logs = AuditLog::query()
            ->with('adminUser')
            ->latest()
            ->paginate(20);

        return view('admin.audit-logs.index', [
            'logs' => $logs,
        ]);
    }

    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load(['report', 'user']);

        if ($claim->status !== 'pending') {
            return back()->with('success', 'Only pending claims can be reviewed at this stage.');
        }

        if (! $claim->report || $claim->report->type !== 'found') {
            return back()->withErrors(['claim' => 'Only found item claims can be approved.']);
        }

        $validated = $request->validate([
            'payment_required' => ['nullable', 'boolean'],
            'payment_amount' => ['nullable', 'integer', 'min:1000'],
            'payment_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentRequired = (bool) ($validated['payment_required'] ?? false);

        if ($paymentRequired) {
            if (empty($validated['payment_amount']) || empty($validated['payment_reason'])) {
                return back()->withErrors([
                    'claim' => 'Payment amount and reason are required when payment is required.',
                ]);
            }

            DB::transaction(function () use ($claim, $request, $validated) {
                $claim->update([
                    'status' => 'awaiting_payment',
                    'held_at' => null,
                    'payment_required' => true,
                    'payment_amount' => (int) $validated['payment_amount'],
                    'payment_reason' => (string) $validated['payment_reason'],
                    'payment_status' => 'pending',
                    'payment_pidx' => null,
                    'payment_completed_at' => null,
                ]);

                if ($claim->user_id) {
                    Notification::create([
                        'user_id' => $claim->user_id,
                        'type' => 'claim_payment_required',
                        'title' => 'Payment Required for Claim',
                        'message' => 'Admin reviewed your claim and requested payment before final verification.',
                        'related_report_id' => $claim->item_id,
                        'related_claim_id' => $claim->id,
                    ]);
                }

                $this->logAdminAction(
                    $request,
                    'claim_payment_required',
                    $claim,
                    'Admin marked claim as awaiting payment.',
                    [
                        'claim_id' => $claim->id,
                        'payment_amount' => (int) $validated['payment_amount'],
                        'payment_reason' => (string) $validated['payment_reason'],
                    ]
                );
            });

            return back()->with('success', 'Claim moved to awaiting payment.');
        }

        DB::transaction(function () use ($claim, $request) {
            $claim->update([
                'status' => 'under_verification',
                'held_at' => null,
                'payment_required' => false,
                'payment_amount' => null,
                'payment_reason' => null,
                'payment_status' => null,
                'payment_pidx' => null,
                'payment_completed_at' => null,
            ]);

            if ($claim->user_id) {
                Notification::create([
                    'user_id' => $claim->user_id,
                    'type' => 'claim_under_verification',
                    'title' => 'Claim Under Verification',
                    'message' => 'Admin reviewed your claim and moved it to under verification.',
                    'related_report_id' => $claim->item_id,
                    'related_claim_id' => $claim->id,
                ]);
            }

            $this->logAdminAction(
                $request,
                'claim_under_verification',
                $claim,
                'Claim moved to under verification by admin.',
                ['claim_id' => $claim->id]
            );
        });

        return back()->with('success', 'Claim moved to under verification.');
    }

    public function finalApprove(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load(['report', 'user']);

        if ($claim->status !== 'under_verification') {
            return back()->withErrors([
                'claim' => 'Only claims under verification can be finally approved.',
            ]);
        }

        if (! $claim->report || $claim->report->type !== 'found') {
            return back()->withErrors(['claim' => 'Only found item claims can be approved.']);
        }

        $alreadyApproved = Claim::query()
            ->where('item_id', $claim->item_id)
            ->where('status', 'approved')
            ->where('id', '!=', $claim->id)
            ->exists();

        if ($alreadyApproved) {
            return back()->withErrors(['claim' => 'Another claim for this item is already approved.']);
        }

        DB::transaction(function () use ($claim) {
            $claim->update([
                'status' => 'approved',
                'held_at' => null,
            ]);

            $report = $claim->report()->select('id', 'user_id', 'status')->first();

            if ($report && $report->status !== 'closed') {
                $report->update(['status' => 'closed']);
            }

            $otherOpenClaims = Claim::query()
                ->where('item_id', $claim->item_id)
                ->whereIn('status', ['pending', 'awaiting_payment', 'under_verification'])
                ->where('id', '!=', $claim->id)
                ->get();

            foreach ($otherOpenClaims as $openClaim) {
                $openClaim->update([
                    'status' => 'rejected',
                    'held_at' => null,
                ]);

                Notification::create([
                    'user_id' => $openClaim->user_id,
                    'type' => 'claim_rejected',
                    'title' => 'Claim Rejected',
                    'message' => 'Another claim for this item was finally approved by admin, so your claim was rejected.',
                    'related_report_id' => $openClaim->item_id,
                    'related_claim_id' => $openClaim->id,
                ]);
            }

            if ($report && Schema::hasTable('chat_conversations')) {
                try {
                    ChatConversation::firstOrCreate(
                        ['claim_id' => $claim->id],
                        [
                            'finder_id' => $report->user_id,
                            'claimant_id' => $claim->user_id,
                            'approved_at' => now(),
                        ]
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to create chat conversation', [
                        'claim_id' => $claim->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        if ($claim->user_id) {
            Notification::create([
                'user_id' => $claim->user_id,
                'type' => 'claim_approved',
                'title' => 'Claim Approved',
                'message' => 'Your claim has been finally approved. You can now chat with the finder through the system.',
                'related_report_id' => $claim->item_id,
                'related_claim_id' => $claim->id,
            ]);
        }

        if ($claim->report && $claim->report->user_id) {
            Notification::create([
                'user_id' => $claim->report->user_id,
                'type' => 'report_comment',
                'title' => 'Claim Approved for Your Item',
                'message' => 'A claim on your found item was finally approved. You can now chat with the claimant.',
                'related_report_id' => $claim->item_id,
                'related_claim_id' => $claim->id,
            ]);
        }

        $this->logAdminAction(
            $request,
            'claim_final_approved',
            $claim,
            'Claim finally approved by admin.',
            ['claim_id' => $claim->id]
        );

        return back()->with('success', 'Claim finally approved.');
    }

    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load(['report', 'user']);

        if (in_array($claim->status, ['approved', 'rejected'], true)) {
            return back()->with('success', 'Claim status is already decided.');
        }

        DB::transaction(function () use ($claim, $request) {
            $claim->update([
                'status' => 'rejected',
                'held_at' => null,
            ]);

            if ($claim->user_id) {
                Notification::create([
                    'user_id' => $claim->user_id,
                    'type' => 'claim_rejected',
                    'title' => 'Claim Rejected',
                    'message' => 'Your claim was reviewed by admin and rejected.',
                    'related_report_id' => $claim->item_id,
                    'related_claim_id' => $claim->id,
                ]);
            }

            $this->logAdminAction(
                $request,
                'claim_rejected',
                $claim,
                'Claim rejected by admin.',
                ['claim_id' => $claim->id]
            );
        });

        return back()->with('success', 'Claim rejected.');
    }

    public function hold(Request $request, Claim $claim): RedirectResponse
    {
        if (! in_array($claim->status, ['pending', 'under_verification'], true)) {
            return back()->with('success', 'Only pending or under verification claims can be put on hold.');
        }

        $claim->update([
            'held_at' => now(),
        ]);

        $this->logAdminAction(
            $request,
            'claim_held',
            $claim,
            'Claim put on hold by admin.',
            ['claim_id' => $claim->id]
        );

        return back()->with('success', 'Claim moved to hold.');
    }

    public function blockUser(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['admin' => 'You cannot block an admin user.']);
        }

        $user->update(['is_blocked' => true]);

        $this->logAdminAction(
            $request,
            'user_blocked',
            $user,
            'User account blocked by admin.',
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return back()->with('success', 'User has been blocked.');
    }

    public function unblockUser(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['admin' => 'Admin users are always allowed.']);
        }

        $user->update(['is_blocked' => false]);

        $this->logAdminAction(
            $request,
            'user_unblocked',
            $user,
            'User account unblocked by admin.',
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return back()->with('success', 'User has been unblocked.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['admin' => 'You cannot delete an admin user.']);
        }

        $fakeClaimsCount = Claim::query()
            ->where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        if ($fakeClaimsCount < 3) {
            return back()->withErrors([
                'admin' => 'User cannot be deleted until they have at least 3 rejected (fake) claims.',
            ]);
        }

        $deletedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $user->delete();

        $this->logAdminAction(
            $request,
            'user_deleted',
            null,
            'User account deleted by admin after repeated fake claims.',
            ['deleted_user' => $deletedUser]
        );

        return back()->with('success', 'User deleted for repeated fake claims.');
    }

    protected function reportRules(): array
    {
        return [
            'reporter_name' => ['required', 'string', 'max:255'],
            'reporter_email' => ['required', 'email', 'max:255'],
            'reporter_phone' => ['required', 'regex:/^\d{10}$/', 'string'],
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'color' => ['required', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:100'],
            'block' => ['required', 'string', 'in:Nepal Block,UK Block,Pokhara City,Pokhara,Unknown'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_note' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:pending,open,closed'],
            'urgency' => ['required', 'in:normal,urgent'],
            'payment_status' => ['nullable', 'in:pending,completed,failed'],
        ];
    }

    protected function adminHomeStats(): array
    {
        $stats = [
            'totalUsers' => 0,
            'totalReports' => 0,
            'pendingClaims' => 0,
            'reportsToday' => 0,
        ];

        if (Schema::hasTable('users')) {
            $stats['totalUsers'] = User::count();
        }

        if (Schema::hasTable('reports')) {
            $stats['totalReports'] = Report::count();
            $stats['reportsToday'] = Report::whereDate('created_at', today())->count();
        }

        if (Schema::hasTable('claims')) {
            $stats['pendingClaims'] = Claim::where('status', 'pending')->count();
        }

        return $stats;
    }

    protected function adminDashboardStats(): array
    {
        $stats = [
            'totalUsers' => 0,
            'totalReports' => 0,
            'openReports' => 0,
            'closedReports' => 0,
            'lostReports' => 0,
            'foundReports' => 0,
            'reportsToday' => 0,
            'pendingClaims' => 0,
            'approvedClaims' => 0,
            'rejectedClaims' => 0,
            'heldClaims' => 0,
        ];

        if (Schema::hasTable('users')) {
            $stats['totalUsers'] = User::count();
        }

        if (Schema::hasTable('reports')) {
            $stats['totalReports'] = Report::where('status', '!=', 'deleted')->count();
            $stats['openReports'] = Report::where('status', 'open')->count();
            $stats['closedReports'] = Report::where('status', 'closed')->count();
            $stats['lostReports'] = Report::where('status', '!=', 'deleted')->where('type', 'lost')->count();
            $stats['foundReports'] = Report::where('status', '!=', 'deleted')->where('type', 'found')->count();
            $stats['reportsToday'] = Report::where('status', '!=', 'deleted')->whereDate('created_at', today())->count();
        }

        if (Schema::hasTable('claims')) {
            $stats['pendingClaims'] = Claim::where('status', 'pending')->count();
            $stats['approvedClaims'] = Claim::where('status', 'approved')->count();
            $stats['rejectedClaims'] = Claim::where('status', 'rejected')->count();
            $stats['heldClaims'] = Claim::whereNotNull('held_at')->where('status', 'pending')->count();
        }

        return $stats;
    }

    protected function buildDailyTrend(string $modelClass): array
    {
        $trend = [];

        for ($offset = 6; $offset >= 0; $offset--) {
            $day = Carbon::today()->subDays($offset);
            $query = $modelClass::whereDate('created_at', $day);
            
            // Exclude deleted reports from trend
            if ($modelClass === Report::class) {
                $query->where('status', '!=', 'deleted');
            }
            
            $trend[] = [
                'label' => $day->format('D'),
                'date' => $day->toDateString(),
                'count' => $query->count(),
            ];
        }

        return $trend;
    }

    protected function logAdminAction(
        Request $request,
        string $action,
        ?Model $auditable,
        string $description,
        ?array $changes = null
    ): void {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        AuditLog::create([
            'admin_user_id' => $request->user()?->id,
            'action' => $action,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'description' => $description,
            'changes' => $changes,
            'ip_address' => $request->ip(),
        ]);
    }
}
