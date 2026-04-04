<!-- Share Report Modal -->
<div id="shareModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 480px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative;">
        
        <!-- Close Button -->
        <button onclick="closeShareModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-gray); border-radius: 6px; transition: background-color 0.2s, color 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-color)'; this.style.color='var(--text-dark)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-gray)'">
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
            <button onclick="shareOnFacebook()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
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
            <button onclick="shareOnTwitter()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
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
            <button onclick="shareOnWhatsApp()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
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
            <button onclick="shareViaEmail()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
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
            <button onclick="copyToClipboard()" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; cursor: pointer; transition: all 0.2s; font-size: 15.5px;" onmouseover="this.style.backgroundColor='#f0f9ff'; this.style.borderColor='#60a5fa'" onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--border-color)'">
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
<div id="shareNotification" style="display: none; position: fixed; bottom: 20px; right: 20px; background: var(--primary); color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 2000; font-size: 14px; font-weight: 600; animation: slideIn 0.3s ease-out;">
    <div id="notificationMessage"></div>
</div>

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

#shareModal {
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

<script>
// Report data passed from the view
const reportData = {
    id: {{ $report->id }},
    title: `{{ addslashes($report->title) }}`,
    description: `{{ addslashes(Str::limit($report->description, 100)) }}`,
    type: `{{ $report->type }}`,
    url: `{{ url('/items/' . $report->id) }}`
};

function openShareModal() {
    document.getElementById('shareModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeShareModal() {
    document.getElementById('shareModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showNotification(message) {
    const notification = document.getElementById('shareNotification');
    document.getElementById('notificationMessage').textContent = message;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function shareOnFacebook() {
    const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(reportData.url)}`;
    window.open(url, 'facebook-share', 'width=600,height=400');
    closeShareModal();
}

function shareOnTwitter() {
    const text = `Check out this ${reportData.type} item: "${reportData.title}" - ${reportData.description.substring(0, 50)}...`;
    const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(reportData.url)}`;
    window.open(url, 'twitter-share', 'width=600,height=400');
    closeShareModal();
}

function shareOnWhatsApp() {
    const text = `Check out this ${reportData.type} item on Lost & Found: "${reportData.title}" - ${reportData.description.substring(0, 50)}... ${reportData.url}`;
    const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
    window.open(url, 'whatsapp-share', 'width=600,height=400');
    closeShareModal();
}

function shareViaEmail() {
    const subject = `Check out this ${reportData.type} item: ${reportData.title}`;
    const body = `I found this ${reportData.type} item on Lost & Found and thought you might be interested:\n\n${reportData.title}\n${reportData.description}\n\nView full details: ${reportData.url}`;
    const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = url;
    closeShareModal();
}

function copyToClipboard() {
    navigator.clipboard.writeText(reportData.url).then(() => {
        showNotification('Link copied to clipboard!');
        closeShareModal();
    }).catch(() => {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = reportData.url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showNotification('Link copied to clipboard!');
        closeShareModal();
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('shareModal');
    if (event.target === modal) {
        closeShareModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeShareModal();
    }
});
</script>
