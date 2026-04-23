<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CertificateRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\LegislationController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --- Kiosk (public self-service terminal) ---
Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/',          [KioskController::class, 'welcome'])->name('welcome');
    Route::get('/identify',  [KioskController::class, 'identifyForm'])->name('identify');
    Route::post('/identify', [KioskController::class, 'identify'])->name('identify.post')->middleware('throttle:10,1');
    Route::get('/home',      [KioskController::class, 'home'])->name('home');
    Route::get('/request',   [KioskController::class, 'requestForm'])->name('request');
    Route::post('/request',  [KioskController::class, 'submitRequest'])->name('request.post')->middleware('throttle:10,1');
    Route::get('/confirm/{id}', [KioskController::class, 'confirm'])->name('confirm');
    Route::get('/status',    [KioskController::class, 'status'])->name('status');
    Route::post('/reset',    [KioskController::class, 'reset'])->name('reset');
});

// --- Landing (guests see welcome, auth users redirect to dashboard) ---
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// --- About (public) ---
Route::get('/about', [AboutController::class, 'index'])->name('about');

// --- Guest Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

// --- Authenticated Routes ---
Route::middleware(['auth', 'active'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Admin Only ---
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Audit Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

        // Barangay Officials management
        Route::resource('officials', OfficialController::class)->except(['show']);

        // Archives
        Route::get('archives', [ArchiveController::class, 'index'])->name('archives.index');
        Route::post('archives/residents/{id}/restore', [ArchiveController::class, 'restoreResident'])->name('archives.residents.restore');
        Route::post('archives/households/{id}/restore', [ArchiveController::class, 'restoreHousehold'])->name('archives.households.restore');
        Route::post('archives/legislation/{id}/restore', [ArchiveController::class, 'restoreLegislation'])->name('archives.legislation.restore');
        Route::post('archives/users/{id}/restore', [ArchiveController::class, 'restoreUser'])->name('archives.users.restore');

        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{certificateRequest}', [PaymentController::class, 'show'])->name('payments.show');
    });

    // --- Admin & Staff ---
    Route::middleware('role:admin,staff')->group(function () {
        // Residents
        Route::resource('residents', ResidentController::class);
        Route::get('residents/{resident}/restore', [ResidentController::class, 'restore'])->name('residents.restore');
        Route::post('residents/import', [ResidentController::class, 'import'])->name('residents.import');

        // Households
        Route::resource('households', HouseholdController::class);

        // Legislation (management routes only)
        Route::resource('legislation', LegislationController::class)->except(['index', 'show']);

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/residents', [ReportController::class, 'residents'])->name('reports.residents');
        Route::get('reports/certificates', [ReportController::class, 'certificates'])->name('reports.certificates');
    });

    // --- Admin, Staff & Signatory ---
    Route::middleware('role:admin,staff,signatory')->group(function () {
        Route::get('certificate-requests', [CertificateRequestController::class, 'index'])->name('certificate-requests.index');
        Route::get('certificate-requests/{certificateRequest}', [CertificateRequestController::class, 'show'])->name('certificate-requests.show');
        Route::patch('certificate-requests/{certificateRequest}/approve', [CertificateRequestController::class, 'approve'])->name('certificate-requests.approve');
        Route::patch('certificate-requests/{certificateRequest}/reject', [CertificateRequestController::class, 'reject'])->name('certificate-requests.reject');
        Route::patch('certificate-requests/{certificateRequest}/release', [CertificateRequestController::class, 'release'])->name('certificate-requests.release');
        Route::get('certificate-requests/{certificateRequest}/print', [CertificateRequestController::class, 'print'])->name('certificate-requests.print');
    });

    // --- Resident ---
    Route::middleware('role:resident')->group(function () {
        Route::get('my/requests', [CertificateRequestController::class, 'myRequests'])->name('my.requests');
        Route::get('my/requests/create', [CertificateRequestController::class, 'create'])->name('my.requests.create');
        Route::post('my/requests', [CertificateRequestController::class, 'store'])->name('my.requests.store');
        Route::get('my/requests/{certificateRequest}', [CertificateRequestController::class, 'show'])->name('my.requests.show');
        Route::get('my/profile', [ResidentController::class, 'myProfile'])->name('my.profile');
        Route::put('my/profile', [ResidentController::class, 'updateMyProfile'])->name('my.profile.update');
    });

    // --- Public (all authenticated) ---
    Route::get('legislation', [LegislationController::class, 'index'])->name('legislation.index');
    Route::get('legislation/{legislation}', [LegislationController::class, 'show'])->name('legislation.show');

    // Messages
    Route::get('messages', [ChatController::class, 'index'])->name('messages.index');
    Route::get('messages/new', [ChatController::class, 'create'])->name('messages.create');
    Route::get('messages/{user}', [ChatController::class, 'show'])->name('messages.show');
    Route::post('messages/{user}', [ChatController::class, 'store'])->name('messages.store');
    Route::get('messages/{user}/sync', [ChatController::class, 'sync'])->name('messages.sync');
    Route::get('messages-unread-count', [ChatController::class, 'unreadCount'])->name('messages.unread-count');
});