<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemReportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'userDashboard'])
        ->middleware('role:user')
        ->name('dashboard');

    Route::middleware('role:user')->group(function () {
        Route::get('/reports/lost/create', [ItemReportController::class, 'createLost'])->name('reports.lost.create');
        Route::post('/reports/lost', [ItemReportController::class, 'storeLost'])->name('reports.lost.store');

        Route::get('/reports/found/create', [ItemReportController::class, 'createFound'])->name('reports.found.create');
        Route::post('/reports/found', [ItemReportController::class, 'storeFound'])->name('reports.found.store');

        Route::get('/items', [ItemReportController::class, 'index'])->name('items.index');
        Route::get('/items/{report}', [ItemReportController::class, 'show'])->name('items.show');
        Route::post('/items/{report}/claims', [ClaimController::class, 'store'])->name('claims.store');

        Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    });

    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/claims', [ClaimController::class, 'adminIndex'])->name('admin.claims.index');
        Route::patch('/admin/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('admin.claims.approve');
        Route::patch('/admin/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('admin.claims.reject');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
