<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RfqTracker;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\CprController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProcurementController;
use App\Livewire\ActivityLogPage;
use App\Livewire\ProcurementTracker;

// ── Auth routes (guest only) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Protected routes ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Root redirect
    Route::get('/', fn() => redirect()->route('rfqs.index'));

    // ── RFQ routes ────────────────────────────────────────────────────────────
    Route::get('/rfqs',                [RfqTracker::class, '__invoke'])->name('rfqs.index');
    Route::get('/rfqs/create',         [RfqController::class, 'create'])->name('rfqs.create');
    Route::post('/rfqs',               [RfqController::class, 'store'])->name('rfqs.store');
    Route::get('/rfqs/{rfq}/print',    [RfqController::class, 'print'])->name('rfqs.print');
    Route::get('/rfqs/{rfq}',          [RfqController::class, 'show'])->name('rfqs.show');
    Route::get('/rfqs/{rfq}/edit',     [RfqController::class, 'edit'])->name('rfqs.edit');
    Route::put('/rfqs/{rfq}',          [RfqController::class, 'update'])->name('rfqs.update');
    Route::delete('/rfqs/{rfq}',       [RfqController::class, 'destroy'])->name('rfqs.destroy');

    // ── Agency routes ─────────────────────────────────────────────────────────
    Route::get('/agencies',               [AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/create',        [AgencyController::class, 'create'])->name('agencies.create');
    Route::get('/agencies/{agency}/edit', [AgencyController::class, 'edit'])->name('agencies.edit');

    // ── CPR Tracker routes ────────────────────────────────────────────────────
    Route::get('/cpr',              [CprController::class, 'index'])->name('cpr.index');
    Route::post('/cpr/scan',        [CprController::class, 'scan'])->name('cpr.scan');
    Route::post('/cpr/dismiss-modal', [CprController::class, 'dismissModal'])->name('cpr.dismissModal');
    Route::get('/cpr/open-pdf',     [CprController::class, 'openPdf'])->name('cpr.open');
    Route::get('/cpr/cancel',       [CprController::class, 'cancelEdit'])->name('cpr.edit.cancel');
    Route::get('/cpr/edit/{id}',    [CprController::class, 'edit'])->name('cpr.edit');
    Route::post('/cpr/update/{id}', [CprController::class, 'update'])->name('cpr.update');
    Route::get('/cpr/progress',     [CprController::class, 'progress'])->name('cpr.progress');
    Route::get('/cpr/results',      [CprController::class, 'results'])->name('cpr.results');
    Route::get('/cpr/search',       [CprController::class, 'search'])->name('cpr.search');

    // ── RFQ Declined route ─────────────────────────────────────────────────
    Route::post('/rfqs/{rfq}/decline', [RfqController::class, 'decline'])->name('rfqs.decline');

    // ── Procurement routes ─────────────────────────────────────────────────
    Route::get('/procurements', [ProcurementTracker::class, '__invoke'])->name('procurements.index');
    Route::get('/procurements/create', [ProcurementController::class, 'create'])->name('procurements.create');
    Route::post('/procurements', [ProcurementController::class, 'store'])->name('procurements.store');
    Route::get('/procurements/{procurement}', [ProcurementController::class, 'show'])->name('procurements.show');
    Route::get('/procurements/{procurement}/edit', [ProcurementController::class, 'edit'])->name('procurements.edit');
    Route::put('/procurements/{procurement}', [ProcurementController::class, 'update'])->name('procurements.update');
    Route::delete('/procurements/{procurement}', [ProcurementController::class, 'destroy'])->name('procurements.destroy');
    Route::get('/procurements/{procurement}/print', [ProcurementController::class, 'print'])->name('procurements.print');
    Route::get('/procurements/{procurement}/export', [ProcurementController::class, 'export'])->name('procurements.export');

    // ── Profile (self-service) ────────────────────────────────────────────────
    Route::get('/profile/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/profile',      [UserController::class, 'profileUpdate'])->name('profile.update');

    // ── Admin only ────────────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::get('/users',             [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',      [UserController::class, 'create'])->name('users.create');
        Route::post('/users',            [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',  [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',       [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',   [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::get('/settings',          [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings',          [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/activity-log',      ActivityLogPage::class)->name('activity-log');
    });

});