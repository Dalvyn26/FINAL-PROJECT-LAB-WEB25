<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRequestService
{
    /**
     * Create a new leave request with proper validation and transaction
     */
    public function createLeaveRequest(array $data, User $user): LeaveRequest
    {
        return DB::transaction(function () use ($data, $user) {
            // Validate if user has sufficient annual leave quota for annual leave
            if ($data['leave_type'] === 'annual') {
                if (!$user->hasSufficientAnnualLeaveQuota($data['total_days'])) {
                    throw new \Exception('Insufficient annual leave quota');
                }
            }

            // Create the leave request with the appropriate initial status
            $status = $data['status'] ?? 'pending'; // Default to pending if not specified

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

    /**
     * Process approval by division leader with transaction
     */
    public function approveByLeader(LeaveRequest $leaveRequest, User $approver, string $note = null): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver, $note) {
            // Validate that the approver is authorized (division leader of the user's division)
            if ($approver->id !== $leaveRequest->user->division->leader_id) {
                throw new \Exception('Unauthorized to approve this leave request');
            }

            // Update the leave request status and approver
            $leaveRequest->update([
                'status' => 'approved_by_leader',
                'approved_by' => $approver->id,
                'leader_note' => $note, // Add the leader note
            ]);

            return $leaveRequest;
        });
    }

    /**
     * Process final approval by HRD with transaction
     */
    public function finalApprove(LeaveRequest $leaveRequest, User $approver): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver) {
            // Validate that the approver is authorized (HRD)
            if ($approver->role !== 'hrd') {
                throw new \Exception('Unauthorized to finalize approval of this leave request');
            }

            // Validate annual leave quota before final approval
            if ($leaveRequest->leave_type === 'annual') {
                if (!$leaveRequest->user->hasSufficientAnnualLeaveQuota($leaveRequest->total_days)) {
                    throw new \Exception("Insufficient annual leave quota for {$leaveRequest->user->name}. Requires {$leaveRequest->total_days} days, but only has {$leaveRequest->user->leave_quota} days available.");
                }
            }

            // Update the leave request status and approver
            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
            ]);

            // PENTING: Kurangi leave_quota di tabel User SESUAI jumlah hari cuti (hanya jika jenis cuti = annual)
            if ($leaveRequest->leave_type === 'annual') {
                $leaveRequest->user->decrement('leave_quota', $leaveRequest->total_days);
            }

            return $leaveRequest;
        });
    }

    /**
     * Process rejection with transaction
     */
    public function reject(LeaveRequest $leaveRequest, User $approver, string $rejectionNote): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approver, $rejectionNote) {
            // Validate that the approver is authorized (division leader or HRD)
            $isDivisionLeader = $approver->role === 'division_leader' && $approver->id === $leaveRequest->user->division->leader_id;
            $isHrd = $approver->role === 'hrd';

            if (!$isDivisionLeader && !$isHrd) {
                throw new \Exception('Unauthorized to reject this leave request');
            }

            // Validate rejection note length (minimum 10 characters)
            if (strlen($rejectionNote) < 10) {
                throw new \Exception('Rejection note must be at least 10 characters');
            }

            // Check if the current status allows rejection
            if ($leaveRequest->status === 'approved') {
                throw new \Exception('Cannot reject an already approved leave request');
            }

            // Store the attachment path to delete it later if needed
            $attachmentPath = $leaveRequest->attachment_path;

            // Update the leave request status and rejection note
            $leaveRequest->update([
                'status' => 'rejected',
                'rejection_note' => $rejectionNote,
                'approved_by' => $approver->id,
            ]);

            return $leaveRequest;
        });
    }

    /**
     * Cancel a leave request with transaction
     */
    public function cancel(LeaveRequest $leaveRequest, User $user): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $user) {
            // Validate that the user is the owner of the leave request
            if ($leaveRequest->user_id !== $user->id) {
                throw new \Exception('Unauthorized to cancel this leave request');
            }

            // Only pending requests can be canceled
            if ($leaveRequest->status !== 'pending') {
                throw new \Exception('Only pending leave requests can be canceled');
            }

            // Store the attachment path to delete it later if needed
            $attachmentPath = $leaveRequest->attachment_path;

            // Update the leave request status
            $leaveRequest->update([
                'status' => 'rejected',
                'rejection_note' => 'Canceled by user',
            ]);

            // Delete the attachment file if it exists
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            return $leaveRequest;
        });
    }

    /**
     * Delete a leave request with transaction
     */
    public function delete(LeaveRequest $leaveRequest, User $user): bool
    {
        return DB::transaction(function () use ($leaveRequest, $user) {
            // Validate that the user is the owner of the leave request
            if ($leaveRequest->user_id !== $user->id) {
                throw new \Exception('Unauthorized to delete this leave request');
            }

            // Only pending requests can be deleted
            if ($leaveRequest->status !== 'pending') {
                throw new \Exception('Only pending leave requests can be deleted');
            }

            // Store the attachment path to delete it later if needed
            $attachmentPath = $leaveRequest->attachment_path;

            $result = $leaveRequest->delete();

            // Delete the attachment file if it exists
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            return $result;
        });
    }
}