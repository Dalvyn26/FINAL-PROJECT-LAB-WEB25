<?php

namespace App\Observers;

use App\Models\LeaveRequest;
use Carbon\Carbon;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "updated" event.
     * Update employee status based on leave request status and dates
     */
    public function updated(LeaveRequest $leaveRequest): void
    {
        // Refresh relationship untuk memastikan data terbaru
        $leaveRequest->load('user');
        
        if ($leaveRequest->user->role === 'admin') {
            return;
        }

        $today = Carbon::today();
        $isInLeaveRange = $today->between($leaveRequest->start_date, $leaveRequest->end_date);
        $isLeaveEnded = $leaveRequest->end_date->lt($today);

        $isApproved = in_array($leaveRequest->status, ['approved', 'approved_by_leader']);

        // Cek apakah ada cuti aktif lainnya (selain cuti saat ini)
        $hasActiveLeave = $leaveRequest->user->leaveRequests()
            ->where('id', '!=', $leaveRequest->id)
            ->whereIn('status', ['approved', 'approved_by_leader'])
            ->where(function($query) use ($today) {
                $query->where(function($q) use ($today) {
                    $q->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today);
                });
            })
            ->exists();

        if ($isApproved && $isInLeaveRange) {
            // Jika cuti disetujui dan sedang dalam masa cuti, ubah status menjadi inactive
            if ($leaveRequest->user->active_status === true) {
                $leaveRequest->user->update(['active_status' => false]);
            }
        } elseif ($isLeaveEnded || $leaveRequest->status === 'rejected' || ($isApproved && $isLeaveEnded)) {
            // Jika masa cuti sudah selesai, ditolak, atau disetujui setelah masa cuti selesai
            // Pastikan status kembali aktif jika tidak ada cuti aktif lainnya
            if (!$hasActiveLeave && $leaveRequest->user->active_status === false) {
                $leaveRequest->user->update(['active_status' => true]);
            }
        }
    }

    /**
     * Handle the LeaveRequest "created" event.
     * Update employee status if leave is approved and already started
     */
    public function created(LeaveRequest $leaveRequest): void
    {
        // Refresh relationship untuk memastikan data terbaru
        $leaveRequest->load('user');
        
        if ($leaveRequest->user->role === 'admin') {
            return;
        }

        $today = Carbon::today();
        $isInLeaveRange = $today->between($leaveRequest->start_date, $leaveRequest->end_date);
        $isApproved = in_array($leaveRequest->status, ['approved', 'approved_by_leader']);

        if ($isApproved && $isInLeaveRange) {
            if ($leaveRequest->user->active_status === true) {
                $leaveRequest->user->update(['active_status' => false]);
            }
        }
    }
}

