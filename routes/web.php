<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\AboutContentController as AdminAboutContentController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Models\AboutContent;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/debug-auth', function () {
    $webUser = Auth::guard('web')->user();
    $adminUser = Auth::guard('admin')->user();
    $anyUser = auth()->user();
    
    return response()->json([
        'web_guard_user' => $webUser ? ['id' => $webUser->id, 'email' => $webUser->email, 'role' => $webUser->role] : null,
        'admin_guard_user' => $adminUser ? ['id' => $adminUser->id, 'email' => $adminUser->email, 'role' => $adminUser->role] : null,
        'auth()->user()' => $anyUser ? ['id' => $anyUser->id, 'email' => $anyUser->email, 'role' => $anyUser->role] : null,
        'session_id' => session()->getId(),
        'session_exists' => session()->exists('user_id'),
    ]);
})->name('debug-auth');

Route::get('/', function () {
    $user = auth()->guard('admin')->user() ?? auth()->guard('web')->user();

    if ($user?->role === 'admin') {
        return redirect()->route('admin.home');
    }

    $recentReports = Report::query()
        ->where('status', 'open')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

    $categories = Report::query()
        ->where('status', 'open')
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    return view('pages.home', [
        'recentReports' => $recentReports,
        'categories' => $categories,
    ]);
})->name('home');

Route::get('/about-us', function () {
    $aboutContents = AboutContent::query()
        ->active()
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    return view('pages.about', compact('aboutContents'));
})->name('about');
Route::view('/contact-us', 'pages.contact')->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/track-report', [ItemReportController::class, 'trackForm'])->name('reports.track.form');
Route::get('/track-report/{reportUid}', [ItemReportController::class, 'trackShow'])->name('reports.track.show');

Route::get('/items', [ItemReportController::class, 'index'])->name('items.index');
Route::get('/items/{report}', [ItemReportController::class, 'show'])->name('items.show');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/verify-email', [AuthController::class, 'showVerifyEmailForm'])->name('verify-email');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email.post');
Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode'])->name('resend-verification-code');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth:web', 'user'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'userDashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'delete'])->name('notifications.delete');

    Route::get('/reports/lost/create', [ItemReportController::class, 'createLost'])->name('reports.lost.create');
    Route::post('/reports/lost', [ItemReportController::class, 'storeLost'])->name('reports.lost.store');
    Route::get('/reports/lost/{report}/edit', [ItemReportController::class, 'editReport'])->name('reports.lost.edit');
    Route::patch('/reports/lost/{report}', [ItemReportController::class, 'updateReport'])->name('reports.lost.update');

    Route::get('/reports/found/create', [ItemReportController::class, 'createFound'])->name('reports.found.create');
    Route::post('/reports/found', [ItemReportController::class, 'storeFound'])->name('reports.found.store');
    Route::get('/reports/found/{report}/edit', [ItemReportController::class, 'editReport'])->name('reports.found.edit');
    Route::patch('/reports/found/{report}', [ItemReportController::class, 'updateReport'])->name('reports.found.update');
    Route::patch('/items/{report}/mark-found', [ItemReportController::class, 'markAsFound'])->name('reports.mark-found');

    Route::post('/items/{report}/sightings', [ItemReportController::class, 'storeSighting'])->name('sightings.store');
    Route::post('/items/{report}/found-responses', [ItemReportController::class, 'storeFoundResponse'])->name('found-responses.store');
    Route::post('/items/{report}/claims', [ClaimController::class, 'store'])->name('claims.store');

    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::post('/claims/{claim}/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    // Urgent Report Payment Routes
    Route::get('/reports/{report}/payment', [PaymentController::class, 'showUrgentReportPayment'])->name('payments.urgent-report');
    Route::post('/reports/{report}/payment/initiate', [PaymentController::class, 'initiateUrgentReportPayment'])->name('payments.urgent-report.initiate');
    Route::get('/reports/{report}/payment/verify', [PaymentController::class, 'verifyUrgentReportPayment'])->name('payments.urgent-report.verify');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:web,admin'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
});

Route::middleware(['auth:web,admin', 'chat'])->group(function () {
    Route::get('/claims/{claim}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/claims/{claim}/chat', [ChatController::class, 'store'])->name('chat.store');
});

Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/admin/home', [AdminController::class, 'home'])->name('admin.home');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/notifications', [AdminController::class, 'notificationsIndex'])->name('admin.notifications.index');
    Route::get('/admin/payments', [AdminController::class, 'paymentsIndex'])->name('admin.payments.index');
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [AdminController::class, 'usersShow'])->name('admin.users.show');
    Route::get('/admin/reports', [AdminController::class, 'reportsIndex'])->name('admin.reports.index');
    Route::get('/admin/reports/export/csv', [AdminController::class, 'reportsExportCsv'])->name('admin.reports.export.csv');
    Route::get('/admin/reports/create/{type?}', [AdminController::class, 'reportsCreate'])->name('admin.reports.create');
    Route::post('/admin/reports', [AdminController::class, 'reportsStore'])->name('admin.reports.store');
    Route::get('/admin/reports/{report}', [AdminController::class, 'reportsShow'])->name('admin.reports.show');
    Route::get('/admin/reports/{report}/edit', [AdminController::class, 'reportsEdit'])->name('admin.reports.edit');
    Route::put('/admin/reports/{report}', [AdminController::class, 'reportsUpdate'])->name('admin.reports.update');
    Route::patch('/admin/reports/{report}/approve', [AdminController::class, 'reportsApprove'])->name('admin.reports.approve');
    Route::patch('/admin/reports/{report}/reject', [AdminController::class, 'reportsReject'])->name('admin.reports.reject');
    Route::patch('/admin/found-responses/{foundResponse}/approve', [AdminController::class, 'foundResponsesApprove'])->name('admin.found-responses.approve');
    Route::patch('/admin/found-responses/{foundResponse}/reject', [AdminController::class, 'foundResponsesReject'])->name('admin.found-responses.reject');
    Route::delete('/admin/reports/{report}', [AdminController::class, 'reportsDestroy'])->name('admin.reports.destroy');
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogsIndex'])->name('admin.audit-logs.index');

    Route::get('/admin/claims', [AdminController::class, 'claimsIndex'])->name('admin.claims.index');
    Route::patch('/admin/claims/{claim}/approve', [AdminController::class, 'approve'])->name('admin.claims.approve');
    Route::patch('/admin/claims/{claim}/final-approve', [AdminController::class, 'finalApprove'])->name('admin.claims.final-approve');
    Route::patch('/admin/claims/{claim}/reject', [AdminController::class, 'reject'])->name('admin.claims.reject');
    Route::patch('/admin/claims/{claim}/hold', [AdminController::class, 'hold'])->name('admin.claims.hold');
    Route::patch('/admin/users/{user}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');
    Route::patch('/admin/users/{user}/unblock', [AdminController::class, 'unblockUser'])->name('admin.users.unblock');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/admin/contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact-messages.index');
    Route::get('/admin/contact-messages/{message}', [AdminContactMessageController::class, 'show'])->name('admin.contact-messages.show');
    Route::post('/admin/contact-messages/{message}/respond', [AdminContactMessageController::class, 'respond'])->name('admin.contact-messages.respond');
    Route::delete('/admin/contact-messages/{message}', [AdminContactMessageController::class, 'delete'])->name('admin.contact-messages.delete');

    Route::get('/admin/about-content', [AdminAboutContentController::class, 'index'])->name('admin.about-contents.index');
    Route::get('/admin/about-content/create', [AdminAboutContentController::class, 'create'])->name('admin.about-contents.create');
    Route::post('/admin/about-content', [AdminAboutContentController::class, 'store'])->name('admin.about-contents.store');
    Route::get('/admin/about-content/{content}', [AdminAboutContentController::class, 'show'])->name('admin.about-contents.show');
    Route::get('/admin/about-content/{content}/edit', [AdminAboutContentController::class, 'edit'])->name('admin.about-contents.edit');
    Route::put('/admin/about-content/{content}', [AdminAboutContentController::class, 'update'])->name('admin.about-contents.update');
    Route::delete('/admin/about-content/{content}', [AdminAboutContentController::class, 'destroy'])->name('admin.about-contents.destroy');

    Route::get('/admin/articles', [AdminArticleController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/articles/create', [AdminArticleController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/articles', [AdminArticleController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/articles/{article}', [AdminArticleController::class, 'show'])->name('admin.articles.show');
    Route::get('/admin/articles/{article}/edit', [AdminArticleController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/admin/articles/{article}', [AdminArticleController::class, 'update'])->name('admin.articles.update');
    Route::delete('/admin/articles/{article}', [AdminArticleController::class, 'destroy'])->name('admin.articles.destroy');

    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

Route::get('/react-test', function () {
    return \Inertia\Inertia::render('Test');
});
