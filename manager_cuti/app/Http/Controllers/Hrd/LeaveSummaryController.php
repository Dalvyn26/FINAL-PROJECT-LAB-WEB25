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
        
        if ($user->role !== 'hrd') {
            abort(403, 'Unauthorized access to HRD leave summary');
        }
        
        $status = $request->get('status', 'all');
        $division = $request->get('division', 'all');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $totalRequests = LeaveRequest::query()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        $approvedRequests = LeaveRequest::query()
            ->where('status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
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
        
        $rejectedRequests = LeaveRequest::query()
            ->where('status', 'rejected')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
        
        $query = LeaveRequest::with(['user', 'user.division', 'approver']);
        
        if ($status !== 'all') {
            if ($status === 'pending') {
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
                $query->where('status', $status)
                      ->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            }
        } else {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }
        
        if ($division !== 'all') {
            $query->whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division);
            });
        }
        
        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
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

