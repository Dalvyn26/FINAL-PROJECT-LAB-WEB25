<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    protected LeaveRequestService $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    /**
     * Display a listing of the leave requests.
     */
    public function index()
    {
        $user = Auth::user();
        
        $leaveRequests = match ($user->role) {
            'admin', 'hrd' => LeaveRequest::with(['user', 'user.division', 'approver'])->paginate(10),
            'division_leader' => LeaveRequest::whereHas('user', function ($query) use ($user) {
                $query->where('division_id', $user->divisionLeader->id ?? 0);
            })->with(['user', 'approver'])->paginate(10),
            'user' => $user->leaveRequests()->with('approver')->paginate(10),
            default => collect()
        };

        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        return view('leave-requests.create');
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => ['required', Rule::in(['annual', 'sick'])],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // For sick leave
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $endDate->diffInDays($startDate) + 1;

        // Handle file upload if present
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        // Additional validation for sick leave
        if ($request->leave_type === 'sick' && !$attachmentPath) {
            return redirect()->back()
                ->withErrors(['attachment' => 'Medical certificate is required for sick leave'])
                ->withInput();
        }

        try {
            $leaveRequest = $this->leaveRequestService->createLeaveRequest([
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'attachment_path' => $attachmentPath,
            ], Auth::user());

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request submitted successfully');
        } catch (\Exception $e) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Authorization check
        $user = Auth::user();
        $canView = $user->id === $leaveRequest->user_id || 
                   $user->isAdmin() || 
                   $user->isHrd() || 
                   ($user->isDivisionLeader() && $leaveRequest->user->division_id === $user->divisionLeader->id);

        if (!$canView) {
            abort(403, 'Unauthorized to view this leave request');
        }

        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified leave request.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        // Only allow editing if the request is still pending and belongs to the current user
        if ($leaveRequest->user_id !== Auth::id() || $leaveRequest->status !== 'pending') {
            abort(403, 'Unauthorized to edit this leave request');
        }

        return view('leave-requests.edit', compact('leaveRequest'));
    }

    /**
     * Update the specified leave request in storage.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // Only allow updating if the request is still pending and belongs to the current user
        if ($leaveRequest->user_id !== Auth::id() || $leaveRequest->status !== 'pending') {
            abort(403, 'Unauthorized to update this leave request');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $endDate->diffInDays($startDate) + 1;

        // Handle file upload if present
        $attachmentPath = $leaveRequest->attachment_path; // Keep existing if no new file
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }
            
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        // Update the leave request
        $leaveRequest->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request updated successfully');
    }

    /**
     * Cancel the specified leave request.
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        try {
            $this->leaveRequestService->cancel($leaveRequest, Auth::user());

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request canceled successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete the specified leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        try {
            $this->leaveRequestService->delete($leaveRequest, Auth::user());

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Approve the leave request by division leader
     */
    public function approveByLeader(LeaveRequest $leaveRequest)
    {
        try {
            $this->leaveRequestService->approveByLeader($leaveRequest, Auth::user());

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Final approve the leave request by HRD
     */
    public function finalApprove(LeaveRequest $leaveRequest)
    {
        try {
            $this->leaveRequestService->finalApprove($leaveRequest, Auth::user());

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Reject the leave request
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        try {
            $this->leaveRequestService->reject($leaveRequest, Auth::user(), $request->rejection_note);

            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request rejected successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}