<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'leader_id' => 'integer',
    ];

    /**
     * Relationship: Division belongs to a Leader (User)
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Relationship: Division has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }

    /**
     * Relationship: Division has many Leave Requests through its users
     */
    public function leaveRequests()
    {
        return $this->hasManyThrough(LeaveRequest::class, User::class, 'division_id', 'user_id');
    }
}