<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * Display a listing of the leave requests based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect to appropriate index based on role
        return match ($user->role) {
            'division_leader' => $this->indexUser(), // Division leader also sees their own leave history
            'hrd' => $this->indexHrd(),
            'admin' => $this->indexAdmin(),
            'user' => $this->indexUser(),
            default => $this->indexUser()
        };
    }

    /**
     * Display a listing of the leave requests for user (karyawan).
     */
    public function indexUser()
    {
        $user = Auth::user();
        $leaveRequests = $user->leaveRequests()->with('approver')->latest()->paginate(10);

        // Calculate statistics
        $currentYear = now()->year;
        $totalLeavesThisYear = $user->leaveRequests()
            ->whereYear('created_at', $currentYear)
            ->count();

        $sickLeavesThisYear = $user->leaveRequests()
            ->where('leave_type', 'sick')
            ->whereYear('created_at', $currentYear)
            ->count();

        return view('leave-requests.index', compact('leaveRequests', 'totalLeavesThisYear', 'sickLeavesThisYear'));
    }

    /**
     * Display a listing of the leave requests for admin.
     */
    public function indexAdmin()
    {
        $leaveRequests = LeaveRequest::with(['user', 'user.division', 'approver'])->paginate(10);

        return view('leave-requests.index-admin', compact('leaveRequests'));
    }

    /**
     * Display a listing of the leave requests for leader.
     */
    public function indexLeader()
    {
        $user = Auth::user();

        // Leader can see pending leave requests from users in their division, excluding their own requests
        $leaveRequests = LeaveRequest::whereHas('user', function ($query) use ($user) {
            $query->where('division_id', $user->divisionLeader->id);
        })
        ->where('user_id', '!=', $user->id) // Exclude leader's own requests
        ->where('status', 'pending')
        ->with(['user', 'approver'])->paginate(10);

        return view('leave-requests.index-leader', compact('leaveRequests'));
    }

    /**
     * Display a listing of the leave requests for HRD.
     */
    public function indexHrd()
    {
        // HRD can see:
        // 1. Requests approved by leaders (from regular staff - status: approved_by_leader)
        // 2. Pending requests from division leaders (which bypass leader approval)
        $leaveRequests = LeaveRequest::where(function ($query) {
            $query->where('status', 'approved_by_leader')
                  ->orWhere(function ($subQuery) {
                      $subQuery->where('status', 'pending')
                               ->whereHas('user', function ($userQuery) {
                                   $userQuery->where('role', 'division_leader');
                               });
                  });
        })
        ->with(['user', 'user.division', 'approver'])
        ->paginate(10);

        return view('leave-requests.index-hrd', compact('leaveRequests'));
    }

    /**
     * Bulk update multiple leave requests
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:leave_requests,id',
            'action' => 'required|in:approve,reject',
            'rejection_note' => $request->action === 'reject' ? 'required|string|min:10|max:500' : 'nullable',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $rejectionNote = $request->rejection_note;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            DB::transaction(function () use ($ids, $action, $rejectionNote, &$successCount, &$errorCount, &$errors) {
                foreach ($ids as $id) {
                    $leaveRequest = LeaveRequest::find($id);

                    if (!$leaveRequest) {
                        $errors[] = "Leave request ID {$id} not found";
                        $errorCount++;
                        continue;
                    }

                    try {
                        if ($action === 'approve') {
                            // Validate annual leave quota
                            if ($leaveRequest->leave_type === 'annual') {
                                if (!$leaveRequest->user->hasSufficientAnnualLeaveQuota($leaveRequest->total_days)) {
                                    $errors[] = "Insufficient leave quota for {$leaveRequest->user->name}'s request (ID: {$id})";
                                    $errorCount++;
                                    continue;
                                }
                            }

                            $this->leaveRequestService->finalApprove($leaveRequest, Auth::user());
                            $successCount++;
                        } elseif ($action === 'reject') {
                            $this->leaveRequestService->reject($leaveRequest, Auth::user(), $rejectionNote);
                            $successCount++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Error processing request ID {$id}: " . $e->getMessage();
                        $errorCount++;
                    }
                }
            });

            if ($successCount > 0) {
                $message = "{$successCount} leave request(s) processed successfully";
                if ($errorCount > 0) {
                    $message .= " with {$errorCount} error(s)";
                }
                session()->flash('success', $message);
            }

            if ($errorCount > 0) {
                session()->flash('bulk_errors', $errors);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'address_during_leave' => 'required|string|max:500',
            'emergency_contact' => 'required|string|max:20',
            'attachment' => $request->leave_type === 'sick'
                ? 'required|file|mimes:pdf,jpg,jpeg,png|max:2048' // Required for sick leave
                : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Optional for annual leave
        ]);

        // Calculate total working days (excluding weekends)
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);

        // Count working days (excluding Saturday and Sunday)
        $totalDays = 0;
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday()) {
                $totalDays++;
            }
            $currentDate->addDay();
        }

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

        // Additional validation for annual leave
        if ($request->leave_type === 'annual') {
            // Check if start_date is at least 3 days from today
            $minStartDate = \Carbon\Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minStartDate)) {
                return redirect()->back()
                    ->withErrors(['start_date' => 'Annual leave must be requested at least 3 days in advance'])
                    ->withInput();
            }

            // Check if user has sufficient leave quota
            $user = Auth::user();
            if ($totalDays > $user->leave_quota) {
                return redirect()->back()
                    ->withErrors(['start_date' => "Insufficient leave quota. You have {$user->leave_quota} days remaining, but requested {$totalDays} days."])
                    ->withInput();
            }
        }

        try {
            $leaveRequest = DB::transaction(function () use ($request, $totalDays, $attachmentPath) {
                // All leave requests start with 'pending' status regardless of role
                // For division leaders, their requests will be visible to HRD for approval
                $initialStatus = 'pending';

                return $this->leaveRequestService->createLeaveRequest([
                    'leave_type' => $request->leave_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'total_days' => $totalDays,
                    'reason' => $request->reason,
                    'address_during_leave' => $request->address_during_leave,
                    'emergency_contact' => $request->emergency_contact,
                    'attachment_path' => $attachmentPath,
                    'status' => $initialStatus,
                ], Auth::user());
            });

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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'address_during_leave' => 'required|string|max:500',
            'emergency_contact' => 'required|string|max:20',
            'attachment' => $request->leave_type === 'sick'
                ? 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'  // Required for sick leave
                : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Optional for annual leave
        ]);

        // Calculate total working days (excluding weekends)
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);

        // Count working days (excluding Saturday and Sunday)
        $totalDays = 0;
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday()) {
                $totalDays++;
            }
            $currentDate->addDay();
        }

        // Handle file upload if present
        $attachmentPath = $leaveRequest->attachment_path; // Keep existing if no new file
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }

            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        // Additional validation for sick leave
        if ($leaveRequest->leave_type === 'sick' && !$attachmentPath) {
            return redirect()->back()
                ->withErrors(['attachment' => 'Medical certificate is required for sick leave'])
                ->withInput();
        }

        // Additional validation for annual leave
        if ($leaveRequest->leave_type === 'annual') {
            // Check if start_date is at least 3 days from today
            $minStartDate = \Carbon\Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minStartDate)) {
                return redirect()->back()
                    ->withErrors(['start_date' => 'Annual leave must be requested at least 3 days in advance'])
                    ->withInput();
            }

            // For updates, we need to consider that quota was already reduced
            // Calculate the difference between old and new total days
            $quotaDiff = $totalDays - $leaveRequest->total_days;

            if ($quotaDiff > 0) {
                // Only check if the additional days exceed the available quota
                $availableQuota = $leaveRequest->user->leave_quota + $leaveRequest->total_days; // Add back the original days
                if ($quotaDiff > $availableQuota) {
                    return redirect()->back()
                        ->withErrors(['start_date' => "Insufficient leave quota. You need {$quotaDiff} more days but only have {$availableQuota} days available."])
                        ->withInput();
                }
            }
        }

        // Update the leave request
        $leaveRequest->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'address_during_leave' => $request->address_during_leave,
            'emergency_contact' => $request->emergency_contact,
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
    public function approveByLeader(Request $request, LeaveRequest $leaveRequest)
    {
        // Validate optional leader note
        $request->validate([
            'leader_note' => 'nullable|string|max:500',
        ]);

        try {
            $this->leaveRequestService->approveByLeader($leaveRequest, Auth::user(), $request->leader_note);

            return redirect()->back()
                ->with('success', 'Leave request approved by leader successfully');
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

            return redirect()->back()
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
            'rejection_note' => 'required|string|min:10|max:500', // Minimum 10 characters
        ]);

        try {
            $this->leaveRequestService->reject($leaveRequest, Auth::user(), $request->rejection_note);

            return redirect()->back()
                ->with('success', 'Leave request rejected successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}