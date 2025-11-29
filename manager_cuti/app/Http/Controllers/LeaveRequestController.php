<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Holiday;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

        return match ($user->role) {
            'division_leader' => $this->indexUser(),
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

        $division = $user->divisionLeader;
        if (!$division) {
            $division = \App\Models\Division::where('leader_id', $user->id)->first();
        }

        if (!$division) {
            abort(404, 'Division not found for this leader');
        }

        $divisionId = $division->id;

        $leaveRequests = LeaveRequest::whereHas('user', function ($query) use ($divisionId) {
            $query->where('division_id', $divisionId);
        })
        ->where('user_id', '!=', $user->id)
        ->where('status', 'pending')
        ->with(['user', 'approver'])->paginate(10);

        return view('leave-requests.index-leader', compact('leaveRequests'));
    }


    /**
     * Display a listing of the leave requests for HRD.
     */
    public function indexHrd()
    {
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
            'leader_note' => $request->action === 'approve' && Auth::user()->role === 'division_leader' ? 'nullable|string|max:500' : 'nullable',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $rejectionNote = $request->rejection_note;
        $leaderNote = $request->leader_note;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            DB::transaction(function () use ($ids, $action, $rejectionNote, $leaderNote, &$successCount, &$errorCount, &$errors) {
                foreach ($ids as $id) {
                    $leaveRequest = LeaveRequest::find($id);

                    if (!$leaveRequest) {
                        $errors[] = "Leave request ID {$id} not found";
                        $errorCount++;
                        continue;
                    }

                    try {
                        $user = Auth::user();
                        
                        if ($action === 'approve') {
                            if ($user->role === 'division_leader') {
                                if ($leaveRequest->leave_type === 'annual') {
                                    if (!$leaveRequest->user->hasSufficientAnnualLeaveQuota($leaveRequest->total_days)) {
                                        $errors[] = "Insufficient leave quota for {$leaveRequest->user->name}'s request (ID: {$id})";
                                        $errorCount++;
                                        continue;
                                    }
                                }
                                
                                $this->leaveRequestService->approveByLeader($leaveRequest, $user, $leaderNote);
                                $successCount++;
                            } else {
                                if ($leaveRequest->leave_type === 'annual') {
                                    if (!$leaveRequest->user->hasSufficientAnnualLeaveQuota($leaveRequest->total_days)) {
                                        $errors[] = "Insufficient leave quota for {$leaveRequest->user->name}'s request (ID: {$id})";
                                        $errorCount++;
                                        continue;
                                    }
                                }

                                $this->leaveRequestService->finalApprove($leaveRequest, $user);
                                $successCount++;
                            }
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
        $user = Auth::user();

        $isEligible = false;
        if ($user->join_date) {
            $isEligible = \Carbon\Carbon::parse($user->join_date)->diffInYears(now()) >= 1;
        }

        return view('leave-requests.create', compact('isEligible'));
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $isEligible = false;
        if (Auth::user()->join_date) {
            $isEligible = \Carbon\Carbon::parse(Auth::user()->join_date)->diffInYears(now()) >= 1;
        }

        if ($request->leave_type === 'annual' && !$isEligible) {
            return redirect()->back()
                ->withErrors(['leave_type' => 'You are not eligible for annual leave yet (Work period under 1 year)'])
                ->withInput();
        }

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

       
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $this->calculateWorkingDays($startDate, $endDate);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        if ($request->leave_type === 'sick' && !$attachmentPath) {
            return redirect()->back()
                ->withErrors(['attachment' => 'Medical certificate is required for sick leave'])
                ->withInput();
        }

        if ($request->leave_type === 'annual') {
            $minStartDate = \Carbon\Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minStartDate)) {
                return redirect()->back()
                    ->withErrors(['start_date' => 'Annual leave must be requested at least 3 days in advance'])
                    ->withInput();
            }

            $user = Auth::user();
            if ($totalDays > $user->leave_quota) {
                return redirect()->back()
                    ->withErrors(['start_date' => "Insufficient leave quota. You have {$user->leave_quota} days remaining, but requested {$totalDays} days."])
                    ->withInput();
            }
        }

        try {
            $leaveRequest = DB::transaction(function () use ($request, $totalDays, $attachmentPath) {
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

        // Calculate total working days (excluding weekends and holidays)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $this->calculateWorkingDays($startDate, $endDate);

        $attachmentPath = $leaveRequest->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }

            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        if ($leaveRequest->leave_type === 'sick' && !$attachmentPath) {
            return redirect()->back()
                ->withErrors(['attachment' => 'Medical certificate is required for sick leave'])
                ->withInput();
        }

        if ($leaveRequest->leave_type === 'annual') {
            $minStartDate = \Carbon\Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minStartDate)) {
                return redirect()->back()
                    ->withErrors(['start_date' => 'Annual leave must be requested at least 3 days in advance'])
                    ->withInput();
            }

            $quotaDiff = $totalDays - $leaveRequest->total_days;

            if ($quotaDiff > 0) {
                $availableQuota = $leaveRequest->user->leave_quota + $leaveRequest->total_days;
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
            'rejection_note' => 'required|string|min:10|max:500',
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

    /**
     * Download PDF surat cuti untuk pengajuan yang sudah approved
     */
    public function downloadPdf(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        $leaveRequest->load(['user.division', 'approver']);

        $canDownload = $user->id === $leaveRequest->user_id || 
                       $user->isAdmin() || 
                       $user->isHrd();

        if (!$canDownload) {
            abort(403, 'Unauthorized to download this leave request');
        }

        if ($leaveRequest->status !== 'approved') {
            return redirect()->back()
                ->withErrors(['error' => 'Surat cuti hanya dapat diunduh untuk pengajuan yang sudah disetujui (Approved)']);
        }

        $logoPath = public_path('logo/LogoCutiin.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = base64_encode($logoData);
        }

        $pdf = Pdf::loadView('pdf.leave_letter', [
            'leaveRequest' => $leaveRequest,
            'user' => $leaveRequest->user,
            'approver' => $leaveRequest->approver,
            'logoBase64' => $logoBase64,
        ]);

        $fileName = 'Surat_Cuti_' . $leaveRequest->user->name . '_' . $leaveRequest->id . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->download($fileName);
    }

    /**
     * Get holidays for a date range (API endpoint for frontend).
     */
    public function getHolidays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $holidays = Holiday::whereBetween('holiday_date', [
            $request->start_date,
            $request->end_date
        ])->get(['holiday_date', 'title'])->map(function ($holiday) {
            return [
                'date' => $holiday->holiday_date->format('Y-m-d'),
                'title' => $holiday->title,
            ];
        });

        return response()->json($holidays);
    }

    /**
     * Get detail of leave request (API endpoint for modal).
     */
    public function getDetail(LeaveRequest $leaveRequest)
    {
        $user = Auth::user();
        $canView = $user->id === $leaveRequest->user_id ||
                   $user->isAdmin() ||
                   $user->isHrd() ||
                   ($user->isDivisionLeader() && $leaveRequest->user->division_id === $user->divisionLeader->id);

        if (!$canView) {
            return response()->json(['error' => 'Unauthorized to view this leave request'], 403);
        }

        $leaveRequest->load(['user.division.leader', 'approver']);

        $timeline = [];
        
        $timeline[] = [
            'status' => 'created',
            'title' => 'Pengajuan Dibuat',
            'description' => 'Pengajuan cuti telah dibuat',
            'datetime' => $leaveRequest->created_at->format('d M Y, H:i'),
            'timestamp' => $leaveRequest->created_at->toIso8601String(),
            'color' => 'slate',
        ];

        $isCanceledByUser = $leaveRequest->status === 'rejected' && 
                           $leaveRequest->rejection_note === 'Canceled by user' && 
                           !$leaveRequest->approver;

        if (!$leaveRequest->user->isDivisionLeader()) {
            $rejectedByHrd = $leaveRequest->status === 'rejected' && 
                            $leaveRequest->approver && 
                            $leaveRequest->approver->isHrd() &&
                            !$isCanceledByUser;
            
            if ($leaveRequest->status === 'pending' && !$isCanceledByUser) {
                $timeline[] = [
                    'status' => 'pending_leader',
                    'title' => 'Pending Review Leader',
                    'description' => 'Menunggu persetujuan dari Leader',
                    'datetime' => null,
                    'timestamp' => null,
                    'color' => 'slate',
                ];
            } elseif ($leaveRequest->status === 'approved_by_leader' || $leaveRequest->status === 'approved') {
                $timeline[] = [
                    'status' => 'approved_leader',
                    'title' => 'Approved by Leader',
                    'description' => $leaveRequest->leader_note ?: 'Pengajuan disetujui oleh Leader',
                    'datetime' => $leaveRequest->updated_at->format('d M Y, H:i'),
                    'timestamp' => $leaveRequest->updated_at->toIso8601String(),
                    'color' => 'green',
                    'approver' => ($leaveRequest->status === 'approved_by_leader' && $leaveRequest->approver) 
                        ? $leaveRequest->approver->name 
                        : ($leaveRequest->user->division && $leaveRequest->user->division->leader 
                            ? $leaveRequest->user->division->leader->name 
                            : null),
                ];
            } elseif ($rejectedByHrd) {
                $timeDiffMinutes = $leaveRequest->created_at->diffInMinutes($leaveRequest->updated_at);
                
                $minMinutes = max(5, floor($timeDiffMinutes * 0.3));
                $maxMinutes = min(floor($timeDiffMinutes * 0.7), $timeDiffMinutes - 10);
                
                $estimatedMinutes = max($minMinutes, min($maxMinutes, floor($timeDiffMinutes * 0.4)));
                
                $approvalDatetime = $leaveRequest->created_at->copy()->addMinutes($estimatedMinutes);
                
                if ($approvalDatetime->lte($leaveRequest->created_at)) {
                    $approvalDatetime = $leaveRequest->created_at->copy()->addMinutes(5);
                }
                
                $minBeforeRejection = $leaveRequest->updated_at->copy()->subMinutes(10);
                if ($approvalDatetime->gte($minBeforeRejection)) {
                    $approvalDatetime = $minBeforeRejection->copy();
                }
                
                if ($approvalDatetime->gte($leaveRequest->updated_at)) {
                    $approvalDatetime = $leaveRequest->updated_at->copy()->subMinutes(10);
                }
                
                $timeline[] = [
                    'status' => 'approved_leader',
                    'title' => 'Approved by Leader',
                    'description' => $leaveRequest->leader_note ?: 'Pengajuan disetujui oleh Leader',
                    'datetime' => $approvalDatetime->format('d M Y, H:i'),
                    'timestamp' => $approvalDatetime->toIso8601String(),
                    'color' => 'green',
                    'approver' => ($leaveRequest->user->division && $leaveRequest->user->division->leader 
                        ? $leaveRequest->user->division->leader->name 
                        : null),
                ];
            } elseif ($leaveRequest->status === 'rejected' && $leaveRequest->approver && $leaveRequest->approver->isDivisionLeader()) {
                $timeline[] = [
                    'status' => 'rejected_leader',
                    'title' => 'Rejected by Leader',
                    'description' => $leaveRequest->rejection_note ?: 'Pengajuan ditolak oleh Leader',
                    'datetime' => $leaveRequest->updated_at->format('d M Y, H:i'),
                    'timestamp' => $leaveRequest->updated_at->toIso8601String(),
                    'color' => 'red',
                    'approver' => $leaveRequest->approver->name,
                ];
            }
        }

        if ($leaveRequest->status === 'approved_by_leader') {
            $timeline[] = [
                'status' => 'pending_hrd',
                'title' => 'Waiting for HRD Approval',
                'description' => 'Menunggu persetujuan dari HRD',
                'datetime' => null,
                'timestamp' => null,
                'color' => 'slate',
            ];
        } elseif ($leaveRequest->status === 'approved') {
            $timeline[] = [
                'status' => 'approved_hrd',
                'title' => 'Approved by HRD',
                'description' => 'Pengajuan disetujui oleh HRD',
                'datetime' => $leaveRequest->updated_at->format('d M Y, H:i'),
                'timestamp' => $leaveRequest->updated_at->toIso8601String(),
                'color' => 'green',
                'approver' => $leaveRequest->approver ? $leaveRequest->approver->name : null,
            ];
        } elseif ($leaveRequest->status === 'rejected' && $leaveRequest->approver && $leaveRequest->approver->isHrd()) {
            $timeline[] = [
                'status' => 'rejected_hrd',
                'title' => 'Rejected by HRD',
                'description' => $leaveRequest->rejection_note ?: 'Pengajuan ditolak oleh HRD',
                'datetime' => $leaveRequest->updated_at->format('d M Y, H:i'),
                'timestamp' => $leaveRequest->updated_at->toIso8601String(),
                'color' => 'red',
                'approver' => $leaveRequest->approver->name,
            ];
        } elseif ($leaveRequest->status === 'pending' && $leaveRequest->user->isDivisionLeader()) {
            $timeline[] = [
                'status' => 'pending_hrd',
                'title' => 'Waiting for HRD Approval',
                'description' => 'Menunggu persetujuan dari HRD',
                'datetime' => null,
                'timestamp' => null,
                'color' => 'slate',
            ];
        }

        if ($isCanceledByUser) {
            $canceledTimeline = [
                'status' => 'canceled',
                'title' => 'Canceled by User',
                'description' => 'Pengajuan cuti dibatalkan oleh pemohon',
                'datetime' => $leaveRequest->updated_at->format('d M Y, H:i'),
                'timestamp' => $leaveRequest->updated_at->toIso8601String(),
                'color' => 'red',
                'approver' => $leaveRequest->user->name,
            ];
            
            $timeline[] = $canceledTimeline;
        }

        usort($timeline, function ($a, $b) {
            if ($a['status'] === 'created') {
                return -1;
            }
            if ($b['status'] === 'created') {
                return 1;
            }
            
            $hasTimestampA = $a['timestamp'] !== null;
            $hasTimestampB = $b['timestamp'] !== null;
            
            if ($hasTimestampA && $hasTimestampB) {
                $timeA = strtotime($a['timestamp']);
                $timeB = strtotime($b['timestamp']);
                
                return ($timeA < $timeB) ? -1 : (($timeA > $timeB) ? 1 : 0);
            }
            
            if ($hasTimestampA && !$hasTimestampB) {
                if ($a['status'] === 'approved_leader' && $b['status'] === 'pending_hrd') {
                    return -1;
                }
                return -1;
            }
            
            if (!$hasTimestampA && $hasTimestampB) {
                if ($b['status'] === 'approved_leader' && $a['status'] === 'pending_hrd') {
                    return 1;
                }
                return 1;
            }
            
            $statusA = $a['status'];
            $statusB = $b['status'];
            
            if ($statusA === 'pending_leader' && $statusB === 'pending_hrd') {
                return -1;
            }
            if ($statusA === 'pending_hrd' && $statusB === 'pending_leader') {
                return 1;
            }
            
            return 0;
        });

        $finalStatusColor = match($leaveRequest->status) {
            'approved' => 'green',
            'pending' => 'orange',
            'rejected' => 'red', // Both canceled and rejected use red
            'approved_by_leader' => 'indigo',
            default => 'slate',
        };

        $finalStatusLabel = match($leaveRequest->status) {
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => $isCanceledByUser ? 'Canceled' : 'Rejected',
            'approved_by_leader' => 'Approved by Leader',
            default => ucfirst(str_replace('_', ' ', $leaveRequest->status)),
        };

        return response()->json([
            'pengajuan' => [
                'id' => $leaveRequest->id,
                'leave_type' => $leaveRequest->leave_type,
                'leave_type_label' => ucfirst($leaveRequest->leave_type) . ' Leave',
                'start_date' => $leaveRequest->start_date->format('d M Y'),
                'end_date' => $leaveRequest->end_date->format('d M Y'),
                'total_days' => $leaveRequest->total_days,
                'reason' => $leaveRequest->reason,
                'address_during_leave' => $leaveRequest->address_during_leave,
                'emergency_contact' => $leaveRequest->emergency_contact,
                'attachment_path' => $leaveRequest->attachment_path,
                'attachment_url' => $leaveRequest->attachment_path ? Storage::url($leaveRequest->attachment_path) : null,
                'status' => $leaveRequest->status,
                'status_label' => $finalStatusLabel,
                'status_color' => $finalStatusColor,
                'rejection_note' => $leaveRequest->rejection_note,
                'leader_note' => $leaveRequest->leader_note,
            ],
            'pemohon' => [
                'name' => $leaveRequest->user->name,
                'email' => $leaveRequest->user->email,
                'nip' => $leaveRequest->user->email, // Using email as NIP if NIP field doesn't exist
                'division' => $leaveRequest->user->division ? $leaveRequest->user->division->name : 'N/A',
            ],
            'timeline' => $timeline,
            'approver' => $leaveRequest->approver ? [
                'name' => $leaveRequest->approver->name,
                'role' => $leaveRequest->approver->role,
            ] : null,
        ]);
    }

    /**
     * Calculate working days excluding weekends and holidays.
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    private function calculateWorkingDays(Carbon $startDate, Carbon $endDate): int
    {
        $holidays = Holiday::whereBetween('holiday_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->pluck('holiday_date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        $totalDays = 0;
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday()) {
                $dateString = $currentDate->format('Y-m-d');
                if (!in_array($dateString, $holidays)) {
                    $totalDays++;
                }
            }
            $currentDate->addDay();
        }

        return $totalDays;
    }
}