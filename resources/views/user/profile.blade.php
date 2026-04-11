@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<section class="split-layout" style="margin-bottom: 20px; gap: 20px;">
    <!-- Profile Header Card -->
    <article class="card">
        <div style="display: grid; gap: 16px;">
            <div style="padding-bottom: 16px; border-bottom: 1px solid var(--line);">
                <h1 style="font-size: 28px; margin: 0 0 8px; display: flex; align-items: center; gap: 10px;">
                    <wa-icon name="user" variant="regular"></wa-icon>
                    <span>{{ $user->name }}</span>
                </h1>
                <p style="color: var(--text-muted); margin: 0;">{{ $user->email }}</p>
                <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 13px; text-transform: capitalize;">{{ $user->role }} Account</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                <div style="background: var(--bg-soft); padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: var(--primary);">{{ $recentActivity['total_reports'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Total Reports</p>
                </div>
                <div style="background: var(--bg-soft); padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: var(--accent);">{{ $recentActivity['total_claims'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Total Claims</p>
                </div>
                <div style="background: #fff3cd; padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: #ff9800;">{{ $recentActivity['pending_claims'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: #666;">Pending Claims</p>
                </div>
            </div>
        </div>
    </article>

    <!-- Activity Sidebar -->
    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Claim Status</h3>
            <div style="display: grid; gap: 8px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>Approved</span>
                    <strong style="color: var(--success);">{{ $recentActivity['approved_claims'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Rejected</span>
                    <strong style="color: var(--danger);">{{ $recentActivity['rejected_claims'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>⏳ Pending</span>
                    <strong style="color: #ff9800;">{{ $recentActivity['pending_claims'] }}</strong>
                </div>
            </div>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Quick Links</h3>
            <div style="display: grid; gap: 8px;">
                <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="text-align: center; font-size: 13px;">Report Lost Item</a>
                <a href="{{ route('reports.found.create') }}" class="btn btn-outline" style="text-align: center; font-size: 13px;">Report Found Item</a>
                <a href="{{ route('claims.index') }}" class="btn btn-ghost" style="text-align: center; font-size: 13px;"><wa-icon name="hand" family="sharp" variant="solid" style="color: rgb(2, 3, 4);"></wa-icon>&nbsp;View My Claims</a>
            </div>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Account Security</h3>
            <p style="margin: 0 0 12px; font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                Update your password or permanently delete your account.
            </p>
            <div style="display: grid; gap: 8px;">
                <button type="button" class="btn btn-outline" style="font-size: 13px;" onclick="document.getElementById('changePasswordSection').scrollIntoView({behavior:'smooth'});">Change Password</button>
                <button type="button" class="btn btn-ghost" style="font-size: 13px; color: var(--danger);" onclick="document.getElementById('deleteAccountSection').scrollIntoView({behavior:'smooth'});">Delete Account</button>
            </div>
        </article>

        @if ($publishedArticles->isNotEmpty())
            <article class="card card-soft">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">📰 Latest Articles</h3>
                <div style="display: grid; gap: 10px;">
                    @foreach ($publishedArticles as $article)
                        <div style="border-bottom: 1px solid var(--line); padding-bottom: 10px;">
                            @if ($loop->last)
                                <div style="border-bottom: none; padding-bottom: 0;">
                            @endif
                            <a href="{{ route('articles.show', $article) }}" style="color: var(--primary); font-weight: 600; font-size: 13px; text-decoration: none; display: block; margin-bottom: 4px; transition: color 0.2s ease;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none';">{{ $article->title }}</a>
                            <p style="margin: 0 0 6px; font-size: 12px; color: var(--text-muted); line-height: 1.4;">{{ \Illuminate\Support\Str::limit($article->short_description, 60) }}</p>
                            <p style="margin: 0; font-size: 11px; color: #999;">{{ $article->created_at->format('M d, Y') }}</p>
                            @if ($loop->last)
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <a href="{{ route('articles.index') }}" class="btn btn-primary" style="text-align: center; font-size: 12px; margin-top: 6px;">Read All Articles</a>
                </div>
            </article>
        @endif
    </aside>
</section>

<section class="grid-2" style="margin-bottom: 20px; gap: 20px;">
    <article class="card" id="changePasswordSection">
        <h2 style="font-size: 22px; margin: 0 0 10px;">Change Password</h2>
        <p class="section-note" style="margin-bottom: 16px;">Use a strong password that you do not reuse elsewhere.</p>

        @if ($errors->changePassword->any())
            <div class="alert alert-error">
                <ul style="padding-left: 18px; margin: 0;">
                    @foreach ($errors->changePassword->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <input class="form-input" type="password" id="current_password" name="current_password" required>
                @error('current_password', 'changePassword')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">New Password</label>
                <input class="form-input" type="password" id="password" name="password" required>
                @error('password', 'changePassword')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </article>

    <article class="card" id="deleteAccountSection">
        <h2 style="font-size: 22px; margin: 0 0 10px;">Delete Account</h2>
        <p class="section-note" style="margin-bottom: 16px;">This will permanently remove your profile, reports, claims, and chat access.</p>

        @if ($errors->deleteAccount->any())
            <div class="alert alert-error">
                <ul style="padding-left: 18px; margin: 0;">
                    @foreach ($errors->deleteAccount->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label class="form-label" for="delete_current_password">Enter Password to Confirm</label>
                <input class="form-input" type="password" id="delete_current_password" name="current_password" required>
                @error('current_password', 'deleteAccount')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn" style="background: #ef4444; color: white;">Delete Account</button>
        </form>
    </article>
</section>

<section style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; margin: 0 0 16px;">My Contact Messages</h2>
    @if ($myContactMessages->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 30px;">
            <p style="color: var(--text-muted); margin: 0;">You haven't sent any contact messages yet.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Contact Us</a>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($myContactMessages as $message)
                <article class="card">
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h3 style="font-size: 17px; margin: 0;">{{ $message->subject }}</h3>
                            <span class="badge {{ $message->status === 'responded' ? 'badge-found' : ($message->status === 'read' ? 'badge-neutral' : 'badge-lost') }}" style="text-transform: capitalize;">{{ $message->status }}</span>
                        </div>
                        <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Sent on {{ $message->created_at->format('M d, Y h:i A') }}</p>
                        <p style="margin: 0; white-space: pre-wrap; line-height: 1.7; color: var(--text-muted);">{{ $message->message }}</p>

                        @if ($message->admin_response)
                            <div style="padding: 14px; background: var(--bg-soft); border-radius: 12px; border-left: 4px solid var(--primary);">
                                <p style="margin: 0 0 6px; font-size: 13px; font-weight: 700; color: var(--text-main);">Admin Reply</p>
                                <p style="margin: 0; white-space: pre-wrap; line-height: 1.7; color: var(--text-muted);">{{ $message->admin_response }}</p>
                            </div>
                        @else
                            <p style="margin: 0; font-size: 13px; color: var(--text-muted);">No reply yet.</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

<!-- My Reports Section -->
<section style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; margin: 0 0 16px;">My Reports</h2>
    @if ($myReports->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 30px;">
            <p style="color: var(--text-muted); margin: 0;">You haven't reported any items yet.</p>
            <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Report an Item</a>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($myReports as $report)
                <article class="card">
                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: start;">
                        @if ($report->image)
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; overflow: hidden; background: var(--bg-soft);">
                                <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; font-size: 32px;">📦</div>
                        @endif

                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 6px; color: var(--text-main);">{{ $report->title }}</h3>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">
                                <strong>{{ ucfirst($report->type) }}</strong> in <strong>{{ $report->category }}</strong>
                            </p>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">{{ $report->location }}</p>
                            <p style="margin: 0 0 6px; font-size: 12px; color: var(--text-muted);">UID: <strong>{{ $report->report_uid ?? '-' }}</strong></p>
                            <p style="margin: 0; font-size: 12px; color: var(--text-muted);">
                                <span class="badge" style="text-transform: capitalize;">{{ $report->status }}</span>
                                {{ $report->claims()->count() }} claim(s)
                            </p>
                        </div>

                        <div style="text-align: right;">
                            <a href="{{ route('items.show', $report) }}" class="btn btn-outline" style="font-size: 13px;">View Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $myReports->links() }}
        </div>
    @endif
</section>

<!-- My Claims Section -->
<section>

<script>
    (function () {
        if (window.customElements && window.customElements.get('wa-icon')) {
            return;
        }

        class WaIconFallback extends HTMLElement {
            connectedCallback() {
                const iconName = (this.getAttribute('name') || '').toLowerCase();

                if (iconName !== 'user') {
                    return;
                }

                this.setAttribute('aria-hidden', 'true');
                this.style.display = 'inline-flex';
                this.style.width = '1em';
                this.style.height = '1em';
                this.style.lineHeight = '1';
                this.style.alignItems = 'center';
                this.style.justifyContent = 'center';
                this.innerHTML = '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"></circle><path d="M5 20C5 16.134 8.134 13 12 13C15.866 13 19 16.134 19 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>';
            }
        }

        window.customElements.define('wa-icon', WaIconFallback);
    })();
</script>
    <h2 style="font-size: 24px; margin: 0 0 16px; display: flex; align-items: center; gap: 8px;"><wa-icon name="hand" family="sharp" variant="solid" style="color: rgb(2, 3, 4);"></wa-icon><span>My Claims</span></h2>
    @if ($myClaims->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 30px;">
            <p style="color: var(--text-muted); margin: 0;">You haven't made any claims yet.</p>
            <a href="{{ route('items.index') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Browse Items</a>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($myClaims as $claim)
                <article class="card">
                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: start;">
                        @if ($claim->report->image)
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; overflow: hidden; background: var(--bg-soft);">
                                <img src="{{ asset('storage/' . $claim->report->image) }}" alt="{{ $claim->report->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; font-size: 32px;">📦</div>
                        @endif

                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 6px; color: var(--text-main);">{{ $claim->report->title }}</h3>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">
                                Claimed on <strong>{{ $claim->created_at->format('M d, Y') }}</strong>
                            </p>
                            <p style="margin: 0; font-size: 12px;">
                                @php
                                    $badgeClass = match($claim->status) {
                                        'approved' => 'badge-found',
                                        'rejected' => 'badge-danger',
                                        default => 'badge'
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}" style="text-transform: capitalize;">{{ $claim->status }}</span>
                            </p>
                        </div>

                        <div style="text-align: right;">
                            <a href="{{ route('items.show', $claim->report) }}" class="btn btn-outline" style="font-size: 13px;">View Item</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $myClaims->links() }}
        </div>
    @endif
</section>
@endsection
