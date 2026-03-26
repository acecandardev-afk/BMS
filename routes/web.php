<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BarangayEventController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\CertificateRequestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\LegislationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --- Landing (guests see welcome, auth users redirect to dashboard) ---
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// --- Public barangay updates (no sign-in required) ---
Route::get('/events', [BarangayEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [BarangayEventController::class, 'show'])
    ->whereNumber('event')
    ->name('events.show');

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

    // Messages (Barangay office & residents)
    Route::get('messages/unread-count', [ChatController::class, 'unreadCount'])->name('messages.unread-count');
    Route::get('messages/new', [ChatController::class, 'create'])->name('messages.create');
    Route::get('messages', [ChatController::class, 'index'])->name('messages.index');
    Route::get('messages/{user}/sync', [ChatController::class, 'sync'])->name('messages.sync');
    Route::get('messages/{user}', [ChatController::class, 'show'])->name('messages.show');
    Route::post('messages/{user}', [ChatController::class, 'store'])
        ->middleware('throttle:60,1')
        ->name('messages.store');

    // --- Admin Only ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/events/create', [BarangayEventController::class, 'create'])->name('events.create');
        Route::post('/events', [BarangayEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [BarangayEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [BarangayEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [BarangayEventController::class, 'destroy'])->name('events.destroy');

        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Audit Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

    // --- Admin & Staff ---
    Route::middleware('role:admin,staff')->group(function () {
        // Residents
        Route::resource('residents', ResidentController::class);
        Route::get('residents/{resident}/restore', [ResidentController::class, 'restore'])->name('residents.restore');

        // Households
        Route::resource('households', HouseholdController::class);

        // Blotter
        Route::resource('blotter', BlotterController::class);
        Route::post('blotter/{blotter}/hearings', [BlotterController::class, 'addHearing'])->name('blotter.hearings.store');
        Route::post('blotter/{blotter}/attachments', [BlotterController::class, 'addAttachment'])->name('blotter.attachments.store');
        Route::patch('blotter/{blotter}/resolve', [BlotterController::class, 'resolve'])->name('blotter.resolve');

        // Legislation (management routes only)
        Route::resource('legislation', LegislationController::class)->except(['index', 'show']);

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/residents', [ReportController::class, 'residents'])->name('reports.residents');
        Route::get('reports/certificates', [ReportController::class, 'certificates'])->name('reports.certificates');
        Route::get('reports/blotter', [ReportController::class, 'blotter'])->name('reports.blotter');
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
});