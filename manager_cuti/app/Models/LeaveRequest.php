<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'address_during_leave',
        'emergency_contact',
        'attachment_path',
        'status',
        'rejection_note',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'integer',
        'user_id' => 'integer',
        'approved_by' => 'integer',
    ];

    /**
     * Relationship: Leave Request belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Leave Request belongs to an Approver (User)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if leave request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if leave request is approved by division leader
     */
    public function isApprovedByLeader(): bool
    {
        return $this->status === 'approved_by_leader';
    }

    /**
     * Check if leave request is fully approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if leave request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if leave request is for annual leave
     */
    public function isAnnualLeave(): bool
    {
        return $this->leave_type === 'annual';
    }

    /**
     * Check if leave request is for sick leave
     */
    public function isSickLeave(): bool
    {
        return $this->leave_type === 'sick';
    }
}