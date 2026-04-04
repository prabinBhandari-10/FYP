@extends('layouts.app')

@section('title', 'Admin Audit Logs | Lost and Found')

@section('content')
<div style="max-width: 1160px; margin: 0 auto; display: grid; gap: 18px;">
    <div class="card" style="margin: 0;">
        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div>
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 6px;">Admin Audit Logs</h2>
                <p style="font-size: 14px; color: var(--text-gray);">Tracks who performed create/update/delete and moderation actions.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>

    <div class="card" style="margin: 0; overflow-x: auto;">
        @if ($logs->count() === 0)
            <p style="font-size: 14px; color: var(--text-gray);">No audit records yet.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; min-width: 960px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Time</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Admin</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Action</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Target</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Description</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $log->created_at?->format('Y-m-d H:i:s') }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $log->adminUser?->name ?? 'System' }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <span class="badge badge-neutral" style="text-transform: none;">{{ $log->action }}</span>
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $log->auditable_type ? class_basename($log->auditable_type) . ' #' . $log->auditable_id : '-' }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray); max-width: 380px;">
                                {{ $log->description }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 16px;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
