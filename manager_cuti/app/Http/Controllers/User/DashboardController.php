<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user()->load(['division.leader']);

        // Get remaining annual leave quota
        $remainingQuota = $user->leave_quota;

        // Count total leave requests by user
        $totalLeaves = LeaveRequest::where('user_id', $user->id)->count();

        // Count total sick leave requests by user
        $totalSickLeave = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'sick')
            ->count();

        return view('user.dashboard', compact('user', 'remainingQuota', 'totalLeaves', 'totalSickLeave'));
    }
}