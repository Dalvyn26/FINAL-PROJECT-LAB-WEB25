<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

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

Route::get('/hrd/dashboard', function () {
    return view('hrd.dashboard');
})->middleware(['auth', 'role:hrd'])->name('hrd.dashboard');

Route::get('/leader/dashboard', function () {
    return view('leader.dashboard');
})->middleware(['auth', 'role:division_leader'])->name('leader.dashboard');

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'role:user'])->name('user.dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('divisions', DivisionController::class);
    Route::resource('users', UserController::class);
});

// Leader Routes
Route::middleware(['auth', 'role:division_leader'])->prefix('leader')->name('leader.')->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'indexLeader'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/approve-by-leader', [LeaveRequestController::class, 'approveByLeader'])->name('leave-requests.approve-by-leader');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});

// HRD Routes
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'indexHrd'])->name('leave-requests.index');
    Route::post('/leave-requests/{leaveRequest}/final-approve', [LeaveRequestController::class, 'finalApprove'])->name('leave-requests.final-approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leave Request Routes
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
});

require __DIR__.'/auth.php';
