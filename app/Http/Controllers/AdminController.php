<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Claim;
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
        $report->load(['user', 'claims.user', 'sightings.user']);

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
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update([
            'status' => 'approved',
            'held_at' => null,
        ]);

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
        if ($claim->status !== 'pending') {
            return back()->with('success', 'Claim status is already decided.');
        }

        $claim->update([
            'status' => 'rejected',
            'held_at' => null,
        ]);

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
            'status' => ['required', 'in:open,closed'],
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
