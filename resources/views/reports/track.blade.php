@extends('layouts.app')

@section('title', 'Track Report | Lost & Found')

@section('content')
<div style="max-width: 860px; margin: 0 auto; display: grid; gap: 16px;">
    <section class="card" style="margin: 0;">
        <h1 class="page-title" style="margin-bottom: 8px;">Track Your Report</h1>
        <p class="page-subtitle" style="margin-bottom: 14px;">Enter your report UID to check the latest progress and approval status.</p>

        <form method="GET" action="{{ route('reports.track.form') }}" style="display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 10px; align-items: center;">
            <input
                class="form-input"
                type="text"
                name="uid"
                value="{{ old('uid', $report->report_uid ?? '') }}"
                placeholder="Enter report UID (e.g. RPT-AB12CD34)"
                style="text-transform: uppercase;"
                required
            >
            <button class="btn btn-primary" type="submit">Track</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-error" style="margin-top: 12px;">
                {{ $errors->first() }}
            </div>
        @endif
    </section>

    @isset($report)
        <section class="card" style="margin: 0;">
            <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 12px;">
                <h2 style="font-size: 22px; margin: 0;">Report Progress</h2>
                <span class="badge badge-neutral" style="text-transform: uppercase;">{{ $report->status }}</span>
            </div>

            @if ($report->status === 'open')
                <div style="background: var(--bg-soft); border: 1px solid var(--line); border-radius: 12px; padding: 14px; margin-bottom: 14px; display: grid; gap: 10px;">
                    <div>
                        <p style="margin: 0 0 4px; font-size: 14px; color: var(--text-muted); font-weight: 700;">Current Status: Approved</p>
                        <p style="margin: 0; font-size: 14px; color: var(--text-main); line-height: 1.6;">
                            Your report has been reviewed by the admin and approved successfully.
                            It is now visible to users for matching and claim purposes.
                        </p>
                    </div>

                    <div>
                        <p style="margin: 0 0 6px; font-size: 14px; color: var(--text-muted); font-weight: 700;">Admin Updates:</p>
                        <ul style="margin: 0; padding-left: 18px; color: var(--text-main); font-size: 14px; line-height: 1.7; display: grid; gap: 2px;">
                            <li>{{ $report->created_at?->format('F d, Y h:i A') }} - Report submitted successfully</li>
                            <li>{{ $report->updated_at?->format('F d, Y h:i A') }} - Admin reviewed your report</li>
                            <li>{{ $report->updated_at?->format('F d, Y h:i A') }} - Report approved and published</li>
                        </ul>
                    </div>

                    <div>
                        <p style="margin: 0 0 4px; font-size: 14px; color: var(--text-muted); font-weight: 700;">Next Step:</p>
                        <p style="margin: 0; font-size: 14px; color: var(--text-main); line-height: 1.6;">
                            Please wait while the system checks for matching found items.
                            You will receive an update if a possible match is found.
                        </p>
                    </div>
                </div>
            @else
                <div style="background: var(--bg-soft); border: 1px solid var(--line); border-radius: 12px; padding: 12px 14px; margin-bottom: 14px;">
                    <p style="margin: 0; font-size: 14px; color: var(--text-main); font-weight: 600;">{{ $statusMessage }}</p>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <p class="section-note">Report UID</p>
                    <p style="font-weight: 800; margin: 0;">{{ $report->report_uid }}</p>
                </div>
                <div>
                    <p class="section-note">Type</p>
                    <p style="font-weight: 700; text-transform: capitalize; margin: 0;">{{ $report->type }}</p>
                </div>
                <div>
                    <p class="section-note">Title</p>
                    <p style="font-weight: 700; margin: 0;">{{ $report->title }}</p>
                </div>
                <div>
                    <p class="section-note">Category</p>
                    <p style="font-weight: 700; margin: 0;">{{ $report->category }}</p>
                </div>
                <div>
                    <p class="section-note">Location</p>
                    <p style="font-weight: 700; margin: 0;">{{ $report->location }}</p>
                </div>
                <div>
                    <p class="section-note">Submitted On</p>
                    <p style="font-weight: 700; margin: 0;">{{ $report->created_at?->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="section-note">Last Updated</p>
                    <p style="font-weight: 700; margin: 0;">{{ $report->updated_at?->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="section-note">Public Visibility</p>
                    <p style="font-weight: 700; margin: 0;">
                        @if ($report->status === 'open')
                            Visible to users
                        @else
                            Hidden until admin approval
                        @endif
                    </p>
                </div>
            </div>
        </section>
    @endisset
</div>
@endsection
