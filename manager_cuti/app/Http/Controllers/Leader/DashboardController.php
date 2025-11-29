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
        
        if ($user->role !== 'division_leader') {
            abort(403, 'Unauthorized access to leader dashboard');
        }
        
        $division = $user->divisionLeader;
        if (!$division) {
            $division = \App\Models\Division::where('leader_id', $user->id)->first();
        }

        if (!$division) {
            abort(404, 'Division not found for this leader');
        }

        $divisionId = $division->id;
        
        $totalRequests = LeaveRequest::whereHas('user', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })->count();
        
        $pendingRequests = LeaveRequest::whereHas('user', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })
        ->where('status', 'pending')
        ->where('user_id', '!=', Auth::id())
        ->count();
        
        $members = User::where('division_id', $divisionId)->get();
        
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        $onLeaveThisWeek = LeaveRequest::with('user')
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where('status', 'approved')
            ->where(function ($query) use ($weekStart, $weekEnd) {
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