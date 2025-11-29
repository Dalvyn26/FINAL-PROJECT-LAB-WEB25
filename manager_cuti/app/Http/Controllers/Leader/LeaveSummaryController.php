<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveSummaryController extends Controller
{
    /**
     * Display the Leader leave summary page (only for division members).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Ensure only division leaders can access this page
        if ($user->role !== 'division_leader') {
            abort(403, 'Unauthorized access to Leader leave summary');
        }

        // Get the division ID for the current leader
        $division = $user->divisionLeader;
        if (!$division) {
            $division = Division::where('leader_id', $user->id)->first();
        }

        if (!$division) {
            abort(404, 'Division not found for this leader');
        }

        $divisionId = $division->id;
        
        // Get filter parameters
        $status = $request->get('status', 'all');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Statistics - Only for division members (exclude leader's own requests)
        // Total Requests: All requests created in selected month/year by division members
        $totalRequests = LeaveRequest::query()
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('user_id', '!=', $user->id) // Exclude leader's own requests
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Approved: Approved requests created in selected month/year
        $approvedRequests = LeaveRequest::query()
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('user_id', '!=', $user->id)
            ->where('status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Pending: All pending requests from division members (no date filter)
        $pendingRequests = LeaveRequest::query()
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('user_id', '!=', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Rejected: Rejected requests created in selected month/year
        $rejectedRequests = LeaveRequest::query()
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('user_id', '!=', $user->id)
            ->where('status', 'rejected')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Base query for leave requests table - Only division members
        $query = LeaveRequest::with(['user', 'user.division', 'approver'])
            ->whereHas('user', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
            ->where('user_id', '!=', $user->id); // Exclude leader's own requests
        
        // Filter by status
        if ($status !== 'all') {
            if ($status === 'pending') {
                // For pending filter, show all pending requests (no date filter)
                $query->where('status', 'pending');
            } else {
                // For other statuses, filter by created_at month/year
                $query->where('status', $status)
                      ->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            }
        } else {
            // For 'all' status, filter by created_at month/year
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('leader.leave-summary', compact(
            'leaveRequests',
            'status',
            'month',
            'year',
            'totalRequests',
            'approvedRequests',
            'pendingRequests',
            'rejectedRequests',
            'division'
        ));
    }
}

