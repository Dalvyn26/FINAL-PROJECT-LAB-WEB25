<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRequestService
{
    public function createLeaveRequest(array $data, User $user): LeaveRequest
    {
        return DB::transaction(function () use ($data, $user) {
            if ($data['leave_type'] === 'annual') {
                if (!$user->hasSufficientAnnualLeaveQuota($data['total_days'])) {
                    throw new \Exception('Insufficient annual leave quota');
                }
            }

            $status = $data['status'] ?? 'pending';

            $leaveRequest = $user->leaveRequests()->create([
                'leave_type' => $data['leave_type'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_days' => $data['total_days'],
                'reason' => $data['reason'],
                'address_during_leave' => $data['address_during_leave'],
                'emergency_contact' => $data['emergency_contact'],
                'attachment_path' => $data['attachment_path'] ?? null,
                'status' => $status,
            ]);

            return $leaveRequest;
        });
    }

    public function approveByLeader(LeaveRequest $leaveRequest, User $approver, string $note = null): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver, $note) {
            if ($approver->id !== $leaveRequest->user->division->leader_id) {
                throw new \Exception('Unauthorized to approve this leave request');
            }

            $leaveRequest->update([
                'status' => 'approved_by_leader',
                'approved_by' => $approver->id,
                'leader_note' => $note,
            ]);

            // Refresh model untuk memastikan Observer terpanggil
            $leaveRequest->refresh();
            
            // Update status karyawan langsung untuk memastikan berjalan
            $this->syncEmployeeStatus($leaveRequest);

            return $leaveRequest;
        });
    }

    public function finalApprove(LeaveRequest $leaveRequest, User $approver, string $note = null): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver, $note) {
            if ($approver->role !== 'hrd') {
                throw new \Exception('Unauthorized to finalize approval of this leave request');
            }

            if ($leaveRequest->leave_type === 'annual') {
                if (!$leaveRequest->user->hasSufficientAnnualLeaveQuota($leaveRequest->total_days)) {
                    throw new \Exception("Insufficient annual leave quota for {$leaveRequest->user->name}. Requires {$leaveRequest->total_days} days, but only has {$leaveRequest->user->leave_quota} days available.");
                }
            }

            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'hrd_note' => $note,
            ]);

            if ($leaveRequest->leave_type === 'annual') {
                $leaveRequest->user->decrement('leave_quota', $leaveRequest->total_days);
            }

            // Refresh model untuk memastikan Observer terpanggil
            $leaveRequest->refresh();
            
            // Update status karyawan langsung untuk memastikan berjalan
            $this->syncEmployeeStatus($leaveRequest);

            return $leaveRequest;
        });
    }

    public function reject(LeaveRequest $leaveRequest, User $approver, string $rejectionNote): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver, $rejectionNote) {
            $isDivisionLeader = $approver->role === 'division_leader' && $approver->id === $leaveRequest->user->division->leader_id;
            $isHrd = $approver->role === 'hrd';

            if (!$isDivisionLeader && !$isHrd) {
                throw new \Exception('Unauthorized to reject this leave request');
            }

            if (strlen($rejectionNote) < 10) {
                throw new \Exception('Rejection note must be at least 10 characters');
            }

            if ($leaveRequest->status === 'approved') {
                throw new \Exception('Cannot reject an already approved leave request');
            }

            $leaveRequest->update([
                'status' => 'rejected',
                'rejection_note' => $rejectionNote,
                'approved_by' => $approver->id,
            ]);

            // Refresh model untuk memastikan Observer terpanggil
            $leaveRequest->refresh();
            
            // Update status karyawan langsung untuk memastikan berjalan
            $this->syncEmployeeStatus($leaveRequest);

            return $leaveRequest;
        });
    }

    public function cancel(LeaveRequest $leaveRequest, User $user): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $user) {
            if ($leaveRequest->user_id !== $user->id) {
                throw new \Exception('Unauthorized to cancel this leave request');
            }

            if ($leaveRequest->status !== 'pending') {
                throw new \Exception('Only pending leave requests can be canceled');
            }

            $attachmentPath = $leaveRequest->attachment_path;

            $leaveRequest->update([
                'status' => 'rejected',
                'rejection_note' => 'Canceled by user',
            ]);

            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            return $leaveRequest;
        });
    }

    public function delete(LeaveRequest $leaveRequest, User $user): bool
    {
        return DB::transaction(function () use ($leaveRequest, $user) {
            if ($leaveRequest->user_id !== $user->id) {
                throw new \Exception('Unauthorized to delete this leave request');
            }

            if ($leaveRequest->status !== 'pending') {
                throw new \Exception('Only pending leave requests can be deleted');
            }

            $attachmentPath = $leaveRequest->attachment_path;

            $result = $leaveRequest->delete();

            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            return $result;
        });
    }

    /**
     * Sync employee status based on leave request
     * This method ensures employee status is updated correctly
     */
    private function syncEmployeeStatus(LeaveRequest $leaveRequest): void
    {
        $user = $leaveRequest->user;
        
        if ($user->role === 'admin') {
            return;
        }

        $today = \Carbon\Carbon::today();
        $isInLeaveRange = $today->between($leaveRequest->start_date, $leaveRequest->end_date);
        $isLeaveEnded = $leaveRequest->end_date->lt($today);
        $isApproved = in_array($leaveRequest->status, ['approved', 'approved_by_leader']);

        // Cek apakah ada cuti aktif lainnya (selain cuti saat ini)
        $hasActiveLeave = $user->leaveRequests()
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
            if ($user->active_status === true) {
                $user->update(['active_status' => false]);
            }
        } elseif ($isLeaveEnded || $leaveRequest->status === 'rejected' || ($isApproved && $isLeaveEnded)) {
            // Jika masa cuti sudah selesai, ditolak, atau disetujui setelah masa cuti selesai
            // Pastikan status kembali aktif jika tidak ada cuti aktif lainnya
            if (!$hasActiveLeave && $user->active_status === false) {
                $user->update(['active_status' => true]);
            }
        }
    }
}