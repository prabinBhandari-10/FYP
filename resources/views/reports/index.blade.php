@extends('layouts.app')

@section('title', 'Browse Items | Lost & Found')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">

    <div style="margin-bottom: 32px;">
        <h1 class="page-title" style="margin-bottom: 8px;">Browse Reported Items</h1>
        <p style="color: var(--text-gray); font-size: 15.5px;">Search and filter lost or found reports, then explore each item.</p>
    </div>

    <div class="card" style="padding: 24px; margin-bottom: 32px; border-radius: 16px;">
        <form method="GET" action="{{ route('items.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input 
                    class="form-input" 
                    type="text" 
                    name="q" 
                    placeholder="Search by title..." 
                    value="{{ request('q') }}"
                    style="border-radius: 999px; padding: 12px 20px;">
            </div>

            <div style="flex: 0 1 200px;">
                <select class="form-select" name="category" style="border-radius: 999px; padding: 12px 20px;">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 0 1 160px;">
                <select class="form-select" name="type" style="border-radius: 999px; padding: 12px 20px;">
                    <option value="">Lost & Found</option>
                    <option value="lost" @selected(request('type') === 'lost')>Lost</option>
                    <option value="found" @selected(request('type') === 'found')>Found</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">Search</button>
        </form>
        
        @guest
            <div style="background-color: var(--bg-color); border: 1px solid var(--border-color); border-radius: 8px; padding: 14px; margin-top: 24px; text-align: center; color: var(--text-gray); font-size: 13.5px;">
                Browsing is public. Log in to report items or submit a claim.
            </div>
        @endguest
    </div>

    @if ($reports->count() === 0)
        <div style="text-align: center; padding: 64px 24px; color: var(--text-light);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 16px;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">No items found</h3>
            <p>We couldn't find anything matching your search criteria.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            @foreach ($reports as $report)
                <div class="card" style="display: flex; flex-direction: column; padding: 0; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 0; position: relative;">
                    
                    <a href="{{ route('items.show', $report) }}" style="text-decoration: none; display: flex; flex-direction: column; height: 100%; color: inherit;">
                        @if ($report->image)
                            <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 200px; background-color: var(--bg-color); display: grid; place-items: center; border-bottom: 1px solid var(--border-color); color: var(--text-light);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        @endif

                        <div style="padding: 24px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1;">
                            <div>
                                @if($report->type === 'lost')
                                    <span class="badge badge-lost">Lost</span>
                                @else
                                    <span class="badge badge-found">Found</span>
                                @endif
                            </div>
                            
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin: 0; line-height: 1.3;">{{ $report->title }}</h3>
                            
                            <div style="display: flex; align-items: center; gap: 6px; color: var(--text-gray); font-size: 14px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                {{ $report->location }}
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: auto;">
                                <span style="font-size: 13px; font-weight: 600; color: var(--text-gray);">{{ $report->category }}</span>
                                <span style="font-size: 13px; color: var(--text-light);">{{ $report->date?->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </a>

                    <!-- Share Button Overlay -->
                    <button onclick="event.preventDefault(); event.stopPropagation(); shareItemFromCard({{ $report->id }}, '{{ addslashes($report->title) }}', '{{ addslashes(Str::limit($report->description, 60)) }}', '{{ $report->type }}', '{{ url('/items/' . $report->id) }}')" style="position: absolute; top: 12px; right: 12px; width: 40px; height: 40px; background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(255, 255, 255, 0.8); border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" onmouseover="this.style.backgroundColor='white'; this.style.boxShadow='0 8px 12px rgba(0, 0, 0, 0.15)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.95)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 40px;">
            {{ $reports->links() }}
        </div>
    @endif
</div>

<style>
    /* Add hover state to cards via a class */
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
    }
</style>

<!-- Share Modal for Item Cards -->
<div id="cardShareModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 480px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative;">
        
        <!-- Close Button -->
        <button onclick="closeCardShareModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-gray); border-radius: 6px; transition: background-color 0.2s, color 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-color)'; this.style.color='var(--text-dark)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-gray)'">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <h2 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0 0 8px; padding-right: 40px;">Share Listing</h2>
        <p style="color: var(--text-gray); margin: 0 0 24px; font-size: 15px;">Help spread the word by sharing this listing on your social networks.</p>

        <!-- Share Options -->
        <div style="display: flex; flex-direction: column; gap: 12px;">

            <!-- Facebook -->
            <button onclick="cardShareOnFacebook()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
                <div style="width: 48px; height: 48px; background: #1877f2; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="white" stroke="none">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div style="text-align: left;">
                    <div style="font-weight: 600; color: var(--text-dark);">Facebook</div>
                    <div style="font-size: 13px; color: var(--text-gray);">Share on Facebook</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: auto; color: var(--text-light);">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Twitter/X -->
            <button onclick="cardShareOnTwitter()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
                <div style="width: 48px; height: 48px; background: #000000; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="white" stroke="none">
                        <path d="M23.953 4.57a10 10 0 002.856 2.905 10.01 10.01 0 003.497 1.6c-.183.057-.37.112-.556.167.005-.001.003-.001.007-.001v.001a10.025 10.025 0 01-1.6-.198 10.02 10.02 0 01-1.93-.46 10 10 0 01-.742-.146 10.025 10.025 0 01-2.005-.6 10.059 10.059 0 01-1.828-1.145 10.001 10.001 0 01-1.395-1.573c-.5-.778-.857-1.616-.857-2.465 0-.748.125-1.467.348-2.147a10.006 10.006 0 011.016-2.318m0-5.57A20.027 20.027 0 012 2.5c-.564 0-1.121.037-1.671.11A20.034 20.034 0 00.45 4.082c-.48.744-.8 1.555-.8 2.468 0 1.159.312 2.268.871 3.215.559.947 1.297 1.77 2.167 2.433.87.663 1.888 1.187 2.974 1.525 1.086.338 2.25.51 3.466.51 1.216 0 2.38-.172 3.466-.51 1.086-.338 2.104-.862 2.974-1.525.87-.663 1.608-1.486 2.167-2.433.559-.947.871-2.056.871-3.215 0-.913-.32-1.724-.8-2.468.135-.424.228-.867.228-1.334z"/>
                    </svg>
                </div>
                <div style="text-align: left;">
                    <div style="font-weight: 600; color: var(--text-dark);">X (Twitter)</div>
                    <div style="font-size: 13px; color: var(--text-gray);">Share on X</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: auto; color: var(--text-light);">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- WhatsApp -->
            <button onclick="cardShareOnWhatsApp()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
                <div style="width: 48px; height: 48px; background: #25d366; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="white" stroke="none">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371 0-.57 0-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.782 1.15l-.383.214-3.976-.762.776 3.898-.256.41a9.964 9.964 0 001.46 5.514c1.231 2.845 3.862 5.164 7.308 5.856 2.531.43 4.982-.235 6.122-1.221a10.02 10.02 0 001.457-10.383 9.96 9.96 0 00-3.495-5.268m8.221-1.275c-1.6.896-2.981.77-4.422.154-.97-.43-1.923-1.073-2.71-1.9-.787-.825-1.467-1.85-1.875-3.013-.656-1.887-.524-3.788.388-5.486.355-.71.948-1.335 1.674-1.782 1.033-.634 2.284-.77 3.544-.385a6.787 6.787 0 012.89 1.616c.74.72 1.278 1.596 1.57 2.55.29.953.24 2.021-.15 3.01a6.77 6.77 0 01-1.309 2.236z"/>
                    </svg>
                </div>
                <div style="text-align: left;">
                    <div style="font-weight: 600; color: var(--text-dark);">WhatsApp</div>
                    <div style="font-size: 13px; color: var(--text-gray);">Share on WhatsApp</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: auto; color: var(--text-light);">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Email -->
            <button onclick="cardShareViaEmail()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
                <div style="width: 48px; height: 48px; background: #7c3aed; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                </div>
                <div style="text-align: left;">
                    <div style="font-weight: 600; color: var(--text-dark);">Email</div>
                    <div style="font-size: 13px; color: var(--text-gray);">Share via email</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: auto; color: var(--text-light);">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Copy Link -->
            <button onclick="cardCopyToClipboard()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
                <div style="width: 48px; height: 48px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                </div>
                <div style="text-align: left;">
                    <div style="font-weight: 600; color: var(--text-dark);">Copy Link</div>
                    <div style="font-size: 13px; color: var(--text-gray);">Copy link to clipboard</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: auto; color: var(--text-light);">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="cardShareNotification" style="display: none; position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 2000; font-size: 14px; font-weight: 600; animation: slideIn 0.3s ease-out;">
    <div id="cardNotificationMessage"></div>
</div>

<script>
// Global variable to store current share data
let currentCardShareData = {};

function shareItemFromCard(id, title, description, type, url) {
    currentCardShareData = { id, title, description, type, url };
    document.getElementById('cardShareModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCardShareModal() {
    document.getElementById('cardShareModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showCardNotification(message) {
    const notification = document.getElementById('cardShareNotification');
    document.getElementById('cardNotificationMessage').textContent = message;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function cardShareOnFacebook() {
    const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentCardShareData.url)}`;
    window.open(url, 'facebook-share', 'width=600,height=400');
    closeCardShareModal();
}

function cardShareOnTwitter() {
    const text = `Check out this ${currentCardShareData.type} item: "${currentCardShareData.title}" - ${currentCardShareData.description}`;
    const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(currentCardShareData.url)}`;
    window.open(url, 'twitter-share', 'width=600,height=400');
    closeCardShareModal();
}

function cardShareOnWhatsApp() {
    const text = `Check out this ${currentCardShareData.type} item on Lost & Found: "${currentCardShareData.title}" - ${currentCardShareData.description} ${currentCardShareData.url}`;
    const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
    window.open(url, 'whatsapp-share', 'width=600,height=400');
    closeCardShareModal();
}

function cardShareViaEmail() {
    const subject = `Check out this ${currentCardShareData.type} item: ${currentCardShareData.title}`;
    const body = `I found this ${currentCardShareData.type} item on Lost & Found and thought you might be interested:\n\n${currentCardShareData.title}\n${currentCardShareData.description}\n\nView full details: ${currentCardShareData.url}`;
    const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = url;
    closeCardShareModal();
}

function cardCopyToClipboard() {
    navigator.clipboard.writeText(currentCardShareData.url).then(() => {
        showCardNotification('Link copied to clipboard!');
        closeCardShareModal();
    }).catch(() => {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = currentCardShareData.url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCardNotification('Link copied to clipboard!');
        closeCardShareModal();
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('cardShareModal');
    if (event.target === modal) {
        closeCardShareModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCardShareModal();
    }
});
</script>
@endsection
