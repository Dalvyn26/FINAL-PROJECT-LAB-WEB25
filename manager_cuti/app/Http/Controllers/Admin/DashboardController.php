<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Total employees (excluding admin role)
        $totalEmployees = User::where('role', '!=', 'admin')->count();

        // Active employees
        $activeEmployees = User::where('role', '!=', 'admin')->where('active_status', true)->count();

        // Inactive employees
        $inactiveEmployees = User::where('role', '!=', 'admin')->where('active_status', false)->count();

        // Total divisions
        $totalDivisions = Division::count();

        // Leave requests created this month
        $leavesThisMonth = LeaveRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Pending leave requests (waiting for approval)
        $pendingLeaves = LeaveRequest::whereIn('status', ['pending', 'approved_by_leader'])->count();

        // New employees (joined within the last year - not yet eligible for full annual leave)
        $newEmployees = User::where('role', '!=', 'admin')
            ->whereNotNull('join_date')
            ->where('join_date', '>', now()->subYear())
            ->with('division')
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'totalDivisions',
            'leavesThisMonth',
            'pendingLeaves',
            'newEmployees'
        ));
    }
}