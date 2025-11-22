<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the HRD dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Ensure only HRD users can access this page
        if ($user->role !== 'hrd') {
            abort(403, 'Unauthorized access to HRD dashboard');
        }
        
        // Total leave requests this month
        $totalLeavesThisMonth = LeaveRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Total requests pending final approval (approved by leader or pending for division leader)
        $pendingFinalApprovals = LeaveRequest::where(function ($query) {
            $query->where('status', 'approved_by_leader')
                  ->orWhere(function ($subquery) {
                      $subquery->where('status', 'pending')
                               ->whereHas('user', function ($userQuery) {
                                   $userQuery->where('role', 'division_leader');
                               });
                  });
        })->count();
        
        // Get current month's start and end dates
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        // Employees on leave this month (approved requests overlapping with current month)
        $onLeaveThisMonth = LeaveRequest::where('status', 'approved')
            ->where(function ($query) use ($monthStart, $monthEnd) {
                // Check if the leave period intersects with the current month
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                      ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                      ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                          $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                      });
            })
            ->with(['user', 'user.division'])
            ->get();
            
        // All divisions with member counts
        $divisions = Division::withCount('users')->get();

        return view('hrd.dashboard', compact(
            'totalLeavesThisMonth',
            'pendingFinalApprovals',
            'onLeaveThisMonth',
            'divisions'
        ));
    }
}