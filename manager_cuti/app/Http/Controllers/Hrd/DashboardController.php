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
        
        // Sync employee status based on leave requests (background check)
        $this->syncEmployeeStatusFromLeaves();
        
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
        
        // Total approved leaves this month
        $approvedLeavesThisMonth = LeaveRequest::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Total rejected leaves this month
        $rejectedLeavesThisMonth = LeaveRequest::where('status', 'rejected')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Get current month's start and end dates
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $today = Carbon::today();
        
        // Employees currently on leave (ongoing - inactive status)
        // These are employees whose leave is currently active (today is between start_date and end_date)
        $employeesCurrentlyOnLeave = LeaveRequest::with(['user', 'user.division'])
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->whereHas('user', function ($query) {
                $query->where('active_status', false)
                      ->where('role', '!=', 'admin');
            })
            ->orderBy('start_date', 'asc')
            ->get();
        
        // Employees on leave this month (all approved leaves that overlap with current month)
        $employeesOnLeave = LeaveRequest::with(['user', 'user.division'])
            ->where('status', 'approved')
            ->where(function ($query) use ($monthStart, $monthEnd) {
                // Leave period overlaps with current month
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                      ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                      ->orWhere(function ($subquery) use ($monthStart, $monthEnd) {
                          $subquery->where('start_date', '<=', $monthStart)
                                   ->where('end_date', '>=', $monthEnd);
                      });
            })
            ->orderBy('start_date', 'asc')
            ->get();
            
        // All divisions with member counts
        $divisions = Division::withCount('users')->get();

        return view('hrd.dashboard', compact(
            'totalLeavesThisMonth',
            'pendingFinalApprovals',
            'approvedLeavesThisMonth',
            'rejectedLeavesThisMonth',
            'employeesCurrentlyOnLeave',
            'employeesOnLeave',
            'divisions'
        ));
    }

    /**
     * Sync employee status based on their leave requests
     * This ensures status is always up-to-date when dashboard is accessed
     */
    private function syncEmployeeStatusFromLeaves(): void
    {
        $today = Carbon::today();
        
        $employees = User::where('role', '!=', 'admin')
            ->with(['leaveRequests' => function($query) {
                $query->whereIn('status', ['approved_by_leader', 'approved'])
                      ->orderBy('end_date', 'desc');
            }])
            ->get();

        foreach ($employees as $employee) {
            $activeLeave = $employee->leaveRequests->first(function($leave) use ($today) {
                return $today->between($leave->start_date, $leave->end_date);
            });

            if ($activeLeave) {
                // Sedang dalam masa cuti, pastikan status inactive
                if ($employee->active_status === true) {
                    $employee->update(['active_status' => false]);
                }
            } else {
                // Tidak ada cuti aktif, pastikan status active
                if ($employee->active_status === false) {
                    $employee->update(['active_status' => true]);
                }
            }
        }
    }
}