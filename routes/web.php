<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
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

Route::view('/about-us', 'pages.about')->name('about');
Route::view('/contact-us', 'pages.contact')->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/track-report', [ItemReportController::class, 'trackForm'])->name('reports.track.form');
Route::get('/track-report/{reportUid}', [ItemReportController::class, 'trackShow'])->name('reports.track.show');

Route::get('/items', [ItemReportController::class, 'index'])->name('items.index');
Route::get('/items/{report}', [ItemReportController::class, 'show'])->name('items.show');
Route::post('/items/{report}/sightings', [ItemReportController::class, 'storeSighting'])->name('sightings.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'userDashboard'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'delete'])->name('notifications.delete');

    Route::get('/reports/lost/create', [ItemReportController::class, 'createLost'])->name('reports.lost.create');
    Route::post('/reports/lost', [ItemReportController::class, 'storeLost'])->name('reports.lost.store');

    Route::get('/reports/found/create', [ItemReportController::class, 'createFound'])->name('reports.found.create');
    Route::post('/reports/found', [ItemReportController::class, 'storeFound'])->name('reports.found.store');

    Route::middleware('role:user')->group(function () {
        Route::post('/items/{report}/claims', [ClaimController::class, 'store'])->name('claims.store');

        Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    });

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::middleware('role:admin')->group(function () {
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
        Route::delete('/admin/reports/{report}', [AdminController::class, 'reportsDestroy'])->name('admin.reports.destroy');
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogsIndex'])->name('admin.audit-logs.index');

        Route::get('/admin/claims', [AdminController::class, 'claimsIndex'])->name('admin.claims.index');
        Route::patch('/admin/claims/{claim}/approve', [AdminController::class, 'approve'])->name('admin.claims.approve');
        Route::patch('/admin/claims/{claim}/reject', [AdminController::class, 'reject'])->name('admin.claims.reject');
        Route::patch('/admin/claims/{claim}/hold', [AdminController::class, 'hold'])->name('admin.claims.hold');
        Route::patch('/admin/users/{user}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');
        Route::patch('/admin/users/{user}/unblock', [AdminController::class, 'unblockUser'])->name('admin.users.unblock');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        Route::get('/admin/contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact-messages.index');
        Route::get('/admin/contact-messages/{message}', [AdminContactMessageController::class, 'show'])->name('admin.contact-messages.show');
        Route::post('/admin/contact-messages/{message}/respond', [AdminContactMessageController::class, 'respond'])->name('admin.contact-messages.respond');
        Route::delete('/admin/contact-messages/{message}', [AdminContactMessageController::class, 'delete'])->name('admin.contact-messages.delete');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
