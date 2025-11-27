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
        
        // Get current month's start and end dates
        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Base query for leave requests
        $query = LeaveRequest::with(['user', 'user.division', 'approver'])
            ->where(function ($q) use ($monthStart, $monthEnd) {
                // Check if the leave period intersects with the selected month
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                  ->orWhere(function ($subq) use ($monthStart, $monthEnd) {
                      $subq->where('start_date', '<=', $monthStart)
                           ->where('end_date', '>=', $monthEnd);
                  });
            });
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter by division
        if ($division !== 'all') {
            $query->whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division);
            });
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get all divisions for filter
        $divisions = Division::all();
        
        // Statistics
        $totalRequests = LeaveRequest::where(function ($q) use ($monthStart, $monthEnd) {
            $q->whereBetween('start_date', [$monthStart, $monthEnd])
              ->orWhereBetween('end_date', [$monthStart, $monthEnd])
              ->orWhere(function ($subq) use ($monthStart, $monthEnd) {
                  $subq->where('start_date', '<=', $monthStart)
                       ->where('end_date', '>=', $monthEnd);
              });
        })->count();
        
        $approvedRequests = LeaveRequest::where('status', 'approved')
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                  ->orWhere(function ($subq) use ($monthStart, $monthEnd) {
                      $subq->where('start_date', '<=', $monthStart)
                           ->where('end_date', '>=', $monthEnd);
                  });
            })->count();
        
        $pendingRequests = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                  ->orWhere(function ($subq) use ($monthStart, $monthEnd) {
                      $subq->where('start_date', '<=', $monthStart)
                           ->where('end_date', '>=', $monthEnd);
                  });
            })->count();
        
        $rejectedRequests = LeaveRequest::where('status', 'rejected')
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('start_date', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                  ->orWhere(function ($subq) use ($monthStart, $monthEnd) {
                      $subq->where('start_date', '<=', $monthStart)
                           ->where('end_date', '>=', $monthEnd);
                  });
            })->count();
        
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

