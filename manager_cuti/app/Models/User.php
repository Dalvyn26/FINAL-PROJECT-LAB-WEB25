<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',
        'phone',
        'address',
        'join_date',
        'leave_quota',
        'active_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'join_date' => 'date',
        'password' => 'hashed',
        'active_status' => 'boolean',
    ];

    /**
     * Relationship: User belongs to a Division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relationship: User has many Leave Requests
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'user_id');
    }

    /**
     * Relationship: User has many Leave Requests as an approver
     */
    public function approvedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    /**
     * Relationship: User can be a division leader
     */
    public function divisionLeader()
    {
        return $this->hasOne(Division::class, 'leader_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is HRD
     */
    public function isHrd(): bool
    {
        return $this->role === 'hrd';
    }

    /**
     * Check if user is division leader
     */
    public function isDivisionLeader(): bool
    {
        return $this->role === 'division_leader';
    }

    /**
     * Check if user is regular user/employee
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has sufficient leave quota for annual leave
     */
    public function hasSufficientAnnualLeaveQuota(int $days): bool
    {
        return $this->leave_quota >= $days;
    }
}