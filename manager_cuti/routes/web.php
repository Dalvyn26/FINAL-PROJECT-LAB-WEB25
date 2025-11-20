<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leave Request Routes
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('/leave-requests/{leaveRequest}/approve-by-leader', [LeaveRequestController::class, 'approveByLeader'])->name('leave-requests.approve-by-leader');
    Route::post('/leave-requests/{leaveRequest}/final-approve', [LeaveRequestController::class, 'finalApprove'])->name('leave-requests.final-approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::post('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
});

require __DIR__.'/auth.php';
