@extends('layouts.app')

@section('title', $report->title . ' | Lost & Found Auburn')

@section('content')
@php
    [$locationBlock, $locationPlace] = array_pad(explode(' - ', (string) $report->location, 2), 2, '');
    if ($locationPlace === '') {
        $locationPlace = $locationBlock;
        $locationBlock = 'Not specified';
    }
@endphp
<div style="max-width: 1000px; margin: 0 auto;">

    <div style="margin-bottom: 32px;">
        <a href="{{ route('items.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-gray); text-decoration: none; font-size: 14.5px; font-weight: 500; margin-bottom: 24px; transition: color 0.2s;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Browse
        </a>
        
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            @if($report->type === 'lost')
                <span class="badge badge-lost">Lost Item</span>
            @else
                <span class="badge badge-found">Found Item</span>
            @endif
            <span class="badge badge-neutral">{{ $report->status }}</span>
        </div>
        
        <h1 class="page-title" style="margin-bottom: 8px;">{{ $report->title }}</h1>
        <p style="color: var(--text-gray); font-size: 15.5px;">Reported on {{ $report->created_at->format('F d, Y') }}</p>
    </div>

    <!-- Grid -->
    <div style="display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr); gap: 32px; align-items: start;">
        
        <!-- Left Side: Image and Description -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="card" style="padding: 0; overflow: hidden; margin: 0;">
                @if ($report->image)
                    <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 300px; background-color: var(--bg-color); display: grid; place-items: center; border-bottom: 1px solid var(--border-color); color: var(--text-light);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                @endif
                
                <div style="padding: 32px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px;">Description</h3>
                    <p style="color: var(--text-gray); line-height: 1.6; font-size: 15px; white-space: pre-wrap;">{{ $report->description }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Details and Claims -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <div class="card" style="margin: 0; padding: 24px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 24px;">Item Details</h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="color: var(--text-light); padding-top: 2px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 2px;">Block</div>
                            <div style="font-size: 15px; color: var(--text-dark); font-weight: 500;">{{ $locationBlock }}</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="color: var(--text-light); padding-top: 2px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 2px;">Place</div>
                            <div style="font-size: 15px; color: var(--text-dark); font-weight: 500;">{{ $locationPlace }}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="color: var(--text-light); padding-top: 2px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 2px;">Date</div>
                            <div style="font-size: 15px; color: var(--text-dark); font-weight: 500;">{{ $report->date?->format('F d, Y') }}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="color: var(--text-light); padding-top: 2px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 2px;">Category</div>
                            <div style="font-size: 15px; color: var(--text-dark); font-weight: 500;">{{ $report->category }}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="color: var(--text-light); padding-top: 2px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-gray); margin-bottom: 2px;">Reporter</div>
                            <div style="font-size: 15px; color: var(--text-dark); font-weight: 500;">{{ $report->user?->name ?? 'Unknown' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claim Section - Only for Found Items -->
            @if($report->type === 'found')
            <div class="card" style="margin: 0; padding: 24px; border-color: var(--primary); background: linear-gradient(180deg, #f8fafc 0%, white 100%);">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px;">Claim Request</h3>
                
                @auth
                    @if ($errors->has('claim'))
                        <div class="alert alert-error" style="margin-bottom: 16px;">{{ $errors->first('claim') }}</div>
                    @endif

                    @if ($existingClaim)
                        <div style="background-color: var(--bg-color); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; text-align: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <p style="font-size: 14.5px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px;">Claim Submitted</p>
                            <p style="font-size: 13.5px; color: var(--text-gray);">Status: <strong>{{ ucfirst($existingClaim->status) }}</strong></p>
                            <div style="margin-top: 16px;">
                                <a href="{{ route('claims.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">View My Claims</a>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('claims.store', $report) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="message">Message</label>
                                <textarea class="form-textarea" id="message" name="message" placeholder="Explain why this item is yours..." style="min-height: 80px;" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="citizenship_document">Citizenship/Nagrikta document</label>
                                <input class="form-input" type="file" id="citizenship_document" name="citizenship_document" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div style="font-size: 12.5px; color: var(--text-light); margin-top: 6px;">Upload a clear scan or photo (JPG, PNG, PDF).</div>
                                @error('citizenship_document')
                                    <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="proof_text">Proof description</label>
                                <textarea class="form-textarea" id="proof_text" name="proof_text" placeholder="Add any proof details (serial number, receipts, etc.)" style="min-height: 80px;">{{ old('proof_text') }}</textarea>
                                @error('proof_text')
                                    <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="proof_photo">Proof photo (optional if you wrote a description)</label>
                                <input class="form-input" type="file" id="proof_photo" name="proof_photo" accept=".jpg,.jpeg,.png">
                                @error('proof_photo')
                                    <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-error" style="margin-bottom: 16px;">
                                Fake claims may lead to legal action based on your identity.
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Submit Claim</button>
                        </form>
                    @endif
                @else
                    <div style="text-align: center; padding: 16px 0;">
                        <p style="font-size: 14px; color: var(--text-gray); margin-bottom: 20px;">Please sign in to submit a claim for this item.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">Login to Claim</a>
                    </div>
                @endauth
            </div>
            @endif

            <!-- Sighting Report Section - Only for Lost Items -->
            @if($report->type === 'lost')
            <div class="card" style="margin: 0; padding: 24px; border-color: #3b82f6; background: linear-gradient(180deg, #f0f9ff 0%, white 100%);">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px;">Report Sighting</h3>
                <p style="font-size: 14px; color: var(--text-gray); margin-bottom: 16px;">Have you seen this item? Send a short message to help locate it.</p>
                
                <form method="POST" action="{{ route('sightings.store', $report) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="sighting_message">Your Message</label>
                        <textarea class="form-textarea" id="sighting_message" name="message" placeholder="Tell us where you saw this item or any helpful details..." style="min-height: 70px;" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sighting_location">Location (optional)</label>
                        <input class="form-input" type="text" id="sighting_location" name="location" placeholder="Where did you see it?" value="{{ old('location') }}">
                        @error('location')
                            <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                        @enderror
                    </div>

                    @guest
                    <div class="form-group">
                        <label class="form-label" for="sighting_name">Your Name</label>
                        <input class="form-input" type="text" id="sighting_name" name="reporter_name" placeholder="Your name" value="{{ old('reporter_name') }}" required>
                        @error('reporter_name')
                            <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sighting_email">Your Email</label>
                        <input class="form-input" type="email" id="sighting_email" name="reporter_email" placeholder="your@email.com" value="{{ old('reporter_email') }}" required>
                        @error('reporter_email')
                            <div style="color: var(--danger); font-size: 12.5px; margin-top: 6px; font-weight: 500;">{{ $message }}</div>
                        @enderror
                    </div>
                    @endguest

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Send Sighting Report</button>
                </form>
            </div>

            <!-- Display Recent Sightings -->
            @if($report->sightings->count() > 0)
            <div class="card" style="margin: 0; padding: 24px; background: #fafbff; border-color: #e0e7ff;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 16px;">Recent Sightings ({{ $report->sightings->count() }})</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($report->sightings->latest()->take(5) as $sighting)
                    <div style="border-left: 3px solid #3b82f6; padding-left: 12px; padding-top: 8px; padding-bottom: 8px;">
                        <p style="margin: 0 0 4px; color: var(--text-dark); font-weight: 600; font-size: 14px;">{{ $sighting->user?->name ?? $sighting->reporter_name }}</p>
                        @if($sighting->location)
                        <p style="margin: 0 0 6px; color: var(--text-gray); font-size: 13px;">📍 {{ $sighting->location }}</p>
                        @endif
                        <p style="margin: 0 0 6px; color: var(--text-gray); font-size: 14px; line-height: 1.4;">{{ $sighting->message }}</p>
                        <p style="margin: 0; color: #9ca3af; font-size: 12px;">{{ $sighting->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
            
        </div>
        
    </div>

</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: minmax"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
