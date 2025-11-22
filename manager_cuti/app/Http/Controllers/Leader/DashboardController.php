<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the division leader dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure only division leaders can access this page
        if ($user->role !== 'division_leader') {
            abort(403, 'Unauthorized access to leader dashboard');
        }
        
        // Get the division ID for the current leader
        $division = $user->divisionLeader;
        if (!$division) {
            // Find which division this user leads by looking for their ID in the leader_id column
            $division = \App\Models\Division::where('leader_id', $user->id)->first();
        }

        if (!$division) {
            abort(404, 'Division not found for this leader');
        }

        $divisionId = $division->id;
        
        // Total leave requests from division members
        $totalRequests = LeaveRequest::whereHas('user', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->count();
        
        // Pending leave requests from division members (need approval)
        // Excluding the leader's own requests to match the approval list
        $pendingRequests = LeaveRequest::whereHas('user', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })
        ->where('status', 'pending')
        ->where('user_id', '!=', Auth::id()) // Important: exclude leader's own requests
        ->count();
        
        // Members in the division
        $members = User::where('division_id', $divisionId)->get();
        
        // Get current week range
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        // Users currently on leave this week (approved leave requests that intersect with current week)
        $onLeaveThisWeek = LeaveRequest::with('user')
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('status', 'approved')
            ->where(function ($query) use ($weekStart, $weekEnd) {
                // Check if the leave period intersects with the current week
                $query->whereBetween('start_date', [$weekStart, $weekEnd])
                      ->orWhereBetween('end_date', [$weekStart, $weekEnd])
                      ->orWhere(function ($q) use ($weekStart, $weekEnd) {
                          $q->where('start_date', '<=', $weekStart)
                            ->where('end_date', '>=', $weekEnd);
                      });
            })
            ->get();

        return view('leader.dashboard', compact(
            'totalRequests', 
            'pendingRequests', 
            'members', 
            'onLeaveThisWeek'
        ));
    }
}