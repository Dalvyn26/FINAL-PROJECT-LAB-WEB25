<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Leader\DashboardController as LeaderDashboardController;
use App\Http\Controllers\Hrd\DashboardController as HrdDashboardController;
use App\Http\Controllers\Hrd\LeaveSummaryController as HrdLeaveSummaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Main Dashboard Route - redirects based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'hrd' => redirect()->route('hrd.dashboard'),
        'division_leader' => redirect()->route('leader.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => view('dashboard')
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::get('/hrd/dashboard', [HrdDashboardController::class, 'index'])->middleware(['auth', 'role:hrd'])->name('hrd.dashboard');

Route::get('/leader/dashboard', [LeaderDashboardController::class, 'index'])->middleware(['auth', 'role:division_leader'])->name('leader.dashboard');

Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'role:user'])->name('user.dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('divisions', DivisionController::class);
    Route::resource('users', 'App\Http\Controllers\Admin\UserController');
    
    // Holiday routes - must be defined before resource to avoid route conflict
    Route::post('/holidays/sync', [HolidayController::class, 'fetchGoogleHolidays'])->name('holidays.sync');
    Route::post('/holidays/bulk-delete', [HolidayController::class, 'bulkDelete'])->name('holidays.bulk-delete');
    Route::resource('holidays', HolidayController::class);

    // Leave Summary for Admin
    Route::get('/leave-summary', [\App\Http\Controllers\Admin\LeaveSummaryController::class, 'index'])->name('leave-summary.index');

    // Division Member Management
    Route::post('/divisions/{division}/members', [DivisionController::class, 'storeMember'])->name('divisions.members.store');
    Route::delete('/divisions/{division}/members/{user}', [DivisionController::class, 'removeMember'])->name('divisions.members.destroy');
});

// Leader Routes
Route::middleware(['auth', 'role:division_leader'])->prefix('leader')->name('leader.')->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'indexLeader'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/approve-by-leader', [LeaveRequestController::class, 'approveByLeader'])->name('leave-requests.approve-by-leader');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::post('/leave-requests/bulk-update', [LeaveRequestController::class, 'bulkUpdate'])->name('leave-requests.bulk-update');
});

// HRD Routes
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'indexHrd'])->name('leave-requests.index');
    Route::get('/leave-summary', [HrdLeaveSummaryController::class, 'index'])->name('leave-summary.index');
    Route::post('/leave-requests/{leaveRequest}/final-approve', [LeaveRequestController::class, 'finalApprove'])->name('leave-requests.final-approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::post('/leave-requests/bulk-update', [LeaveRequestController::class, 'bulkUpdate'])->name('leave-requests.bulk-update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leave Request Routes
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    Route::get('/leave-requests/{leaveRequest}/download-pdf', [LeaveRequestController::class, 'downloadPdf'])->name('leave-requests.download-pdf');
    
    // API: Get holidays for date range (for frontend calculation)
    Route::get('/api/holidays', [LeaveRequestController::class, 'getHolidays'])->name('api.holidays');
});

require __DIR__.'/auth.php';
