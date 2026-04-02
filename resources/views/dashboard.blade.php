@extends('layouts.app')

@section('title', 'User Dashboard | Lost & Found Auburn')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Welcome back, {{ Auth::user()->name }}</h1>
            <p style="color: var(--text-gray); font-size: 15.5px; margin-top: 8px;">Here's what's happening with your items and claims.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('reports.lost.create') }}" class="btn btn-outline">Report Lost Item</a>
            <a href="{{ route('reports.found.create') }}" class="btn btn-primary">Report Found Item</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <!-- Stat Cards -->
        <div class="card" style="margin: 0; padding: 24px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background-color: rgba(67, 56, 202, 0.1); color: var(--primary); display: grid; place-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1.2;">{{ $stats['totalReports'] ?? 0 }}</div>
                <div style="font-size: 13.5px; font-weight: 500; color: var(--text-gray);">Total Reports</div>
            </div>
        </div>

        <div class="card" style="margin: 0; padding: 24px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger); display: grid; place-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1.2;">{{ $stats['lostReports'] ?? 0 }}</div>
                <div style="font-size: 13.5px; font-weight: 500; color: var(--text-gray);">Lost Reports</div>
            </div>
        </div>

        <div class="card" style="margin: 0; padding: 24px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background-color: rgba(34, 197, 94, 0.1); color: #16a34a; display: grid; place-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1.2;">{{ $stats['foundReports'] ?? 0 }}</div>
                <div style="font-size: 13.5px; font-weight: 500; color: var(--text-gray);">Found Reports</div>
            </div>
        </div>

        <div class="card" style="margin: 0; padding: 24px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background-color: rgba(245, 158, 11, 0.1); color: #d97706; display: grid; place-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1.2;">{{ $stats['activeClaims'] ?? 0 }}</div>
                <div style="font-size: 13.5px; font-weight: 500; color: var(--text-gray);">Active Claims</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 32px; align-items: start;">
        
        <!-- Left Column: Quick Actions & Reports -->
        <div style="display: flex; flex-direction: column; gap: 32px;">
            
            <div class="card" style="margin: 0; padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin: 0;">Quick Actions</h2>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <a href="{{ route('items.index') }}" style="display: flex; flex-direction: column; gap: 12px; padding: 20px; border: 1px solid var(--border-color); border-radius: 12px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.backgroundColor='var(--bg-color)';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.backgroundColor='transparent';">
                        <div style="color: var(--primary);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 4px; font-size: 15px;">Browse Items</div>
                            <div style="font-size: 13px; color: var(--text-gray); line-height: 1.4;">Search lost and found items from the community.</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('claims.index') }}" style="display: flex; flex-direction: column; gap: 12px; padding: 20px; border: 1px solid var(--border-color); border-radius: 12px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.backgroundColor='var(--bg-color)';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.backgroundColor='transparent';">
                        <div style="color: var(--primary);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 4px; font-size: 15px;">My Claims</div>
                            <div style="font-size: 13px; color: var(--text-gray); line-height: 1.4;">Check the status of items you've claimed.</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Activity & Notifications -->
        <div style="display: flex; flex-direction: column; gap: 32px;">
            
            <div class="card" style="margin: 0; padding: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin: 0 0 20px 0;">Recent Activity</h2>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--bg-color); display: grid; place-items: center; flex-shrink: 0; color: var(--text-gray);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: var(--text-dark); margin-bottom: 2px;">Lost report submitted</div>
                            <div style="font-size: 12px; color: var(--text-light);">Today · 10:24 AM</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--bg-color); display: grid; place-items: center; flex-shrink: 0; color: var(--text-gray);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: var(--text-dark); margin-bottom: 2px;">Location updated on a report</div>
                            <div style="font-size: 12px; color: var(--text-light);">Yesterday · 6:12 PM</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin: 0; padding: 24px; background-color: rgba(67, 56, 202, 0.03); border: 1px solid rgba(67, 56, 202, 0.1);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <h2 style="font-size: 15px; font-weight: 700; color: var(--primary); margin: 0;">Notifications</h2>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="padding: 12px; background-color: white; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                        <div style="font-size: 13.5px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">Claim under review</div>
                        <div style="font-size: 12.5px; color: var(--text-gray);">Expected response within 24 hours.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: minmax"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection