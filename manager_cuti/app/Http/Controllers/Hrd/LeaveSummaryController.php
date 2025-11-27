<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveSummaryController extends Controller
{
    /**
     * Display the HRD leave summary page.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Ensure only HRD users can access this page
        if ($user->role !== 'hrd') {
            abort(403, 'Unauthorized access to HRD leave summary');
        }
        
        // Get filter parameters
        $status = $request->get('status', 'all');
        $division = $request->get('division', 'all');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Statistics - Match dashboard logic EXACTLY: count by created_at (when request was created)
        // Use fresh query to ensure we get latest data from database
        // Total Requests: All requests created in selected month/year
        $totalRequests = LeaveRequest::query()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Approved: Approved requests created in selected month/year
        $approvedRequests = LeaveRequest::query()
            ->where('status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Pending: All pending requests (no date filter) - Match HRD dashboard logic
        // HRD sees: approved_by_leader OR (pending for division_leader)
        $pendingRequests = LeaveRequest::query()
            ->where(function ($query) {
                $query->where('status', 'approved_by_leader')
                      ->orWhere(function ($subquery) {
                          $subquery->where('status', 'pending')
                                   ->whereHas('user', function ($userQuery) {
                                       $userQuery->where('role', 'division_leader');
                                   });
                      });
            })
            ->count();
        
        // Rejected: Rejected requests created in selected month/year
        $rejectedRequests = LeaveRequest::query()
            ->where('status', 'rejected')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        // Base query for leave requests table - Filter by created_at (when the request was created) to match dashboard logic
        $query = LeaveRequest::with(['user', 'user.division', 'approver']);
        
        // Filter by status
        if ($status !== 'all') {
            if ($status === 'pending') {
                // For pending filter, show all pending requests matching HRD dashboard logic (no date filter)
                $query->where(function ($q) {
                    $q->where('status', 'approved_by_leader')
                      ->orWhere(function ($subq) {
                          $subq->where('status', 'pending')
                               ->whereHas('user', function ($userQuery) {
                                   $userQuery->where('role', 'division_leader');
                               });
                      });
                });
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
        
        // Filter by division
        if ($division !== 'all') {
            $query->whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division);
            });
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Get all divisions for filter
        $divisions = Division::all();
        
        return view('hrd.leave-summary', compact(
            'leaveRequests',
            'divisions',
            'status',
            'division',
            'month',
            'year',
            'totalRequests',
            'approvedRequests',
            'pendingRequests',
            'rejectedRequests'
        ));
    }
}

