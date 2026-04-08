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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'totalUsers' => 0,
            'totalReports' => 0,
            'openReports' => 0,
            'pendingClaims' => 0,
            'approvedClaims' => 0,
            'heldClaims' => 0,
        ];

        $recentReports = collect();
        $recentClaims = collect();

        if (Schema::hasTable('users')) {
            $stats['totalUsers'] = User::count();
        }

        if (Schema::hasTable('reports')) {
            $stats['totalReports'] = Report::count();

            if (Schema::hasColumn('reports', 'status')) {
                $stats['openReports'] = Report::where('status', 'open')->count();
            }

            $recentReports = Report::query()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        if (Schema::hasTable('claims')) {
            if (Schema::hasColumn('claims', 'status')) {
                $stats['pendingClaims'] = Claim::where('status', 'pending')->count();
                $stats['approvedClaims'] = Claim::where('status', 'approved')->count();
            }

            if (Schema::hasColumn('claims', 'held_at')) {
                $stats['heldClaims'] = Claim::whereNotNull('held_at')->where('status', 'pending')->count();
            }

            $recentClaims = Claim::query()
                ->with(['user', 'report'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentReports', 'recentClaims'));
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

        $reports = $query->paginate(12)->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => [
                'q' => (string) $request->string('q'),
                'type' => $type,
                'status' => (string) $request->string('status'),
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
            ]),
            'formTitle' => 'Create ' . ucfirst($type) . ' Report',
            'formAction' => route('admin.reports.store'),
            'submitLabel' => 'Create Report',
            'isEdit' => false,
        ]);
    }

    public function reportsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->reportRules());

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
            'type' => $validated['type'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => $validated['status'],
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
        $validated = $request->validate($this->reportRules());
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
            'category' => $validated['category'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'date' => $validated['date'],
            'image' => $imagePath,
            'status' => $validated['status'],
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

        if ($report->image) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

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
            return back()->with('success', 'Claim status is already decided.');
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

            $otherPendingClaims = Claim::query()
                ->where('item_id', $claim->item_id)
                ->where('status', 'pending')
                ->where('id', '!=', $claim->id)
                ->get();

            foreach ($otherPendingClaims as $pendingClaim) {
                $pendingClaim->update([
                    'status' => 'rejected',
                    'held_at' => null,
                ]);

                Notification::create([
                    'user_id' => $pendingClaim->user_id,
                    'type' => 'claim_rejected',
                    'title' => 'Claim Rejected',
                    'message' => 'Another claim for this item was approved by admin, so your claim was rejected.',
                    'related_report_id' => $pendingClaim->item_id,
                    'related_claim_id' => $pendingClaim->id,
                ]);
            }

            if ($report && Schema::hasTable('chat_conversations')) {
                ChatConversation::firstOrCreate(
                    ['claim_id' => $claim->id],
                    [
                        'finder_id' => $report->user_id,
                        'claimant_id' => $claim->user_id,
                        'approved_at' => now(),
                    ]
                );
            }
        });

        if ($claim->user_id) {
            Notification::create([
                'user_id' => $claim->user_id,
                'type' => 'claim_approved',
                'title' => 'Claim Approved',
                'message' => 'Your claim has been approved. You can now chat with the finder through the system.',
                'related_report_id' => $claim->item_id,
                'related_claim_id' => $claim->id,
            ]);
        }

        if ($claim->report && $claim->report->user_id) {
            Notification::create([
                'user_id' => $claim->report->user_id,
                'type' => 'report_comment',
                'title' => 'Claim Approved for Your Item',
                'message' => 'A claim on your found item was approved. You can now chat with the claimant.',
                'related_report_id' => $claim->item_id,
                'related_claim_id' => $claim->id,
            ]);
        }

        $this->logAdminAction(
            $request,
            'claim_approved',
            $claim,
            'Claim approved by admin.',
            ['claim_id' => $claim->id]
        );

        return back()->with('success', 'Claim approved.');
    }

    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load(['report', 'user']);

        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

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

        return back()->with('success', 'Claim rejected.');
    }

    public function hold(Request $request, Claim $claim): RedirectResponse
    {
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Only pending claims can be put on hold.');
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
            'reporter_phone' => ['required', 'string', 'max:30'],
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:pending,open,closed'],
        ];
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
