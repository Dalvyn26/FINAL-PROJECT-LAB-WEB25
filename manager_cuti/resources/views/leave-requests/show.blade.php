<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-slate-800 leading-tight">
                {{ __('Leave Request Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 transition-all">
                <div class="text-slate-700">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 sm:mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-slate-800">Leave Request Details</h3>
                        <span class="px-3 py-1 inline-flex text-xs sm:text-sm font-bold rounded-full 
                            @if($leaveRequest->isPending()) bg-yellow-100 text-yellow-800
                            @elseif($leaveRequest->isApprovedByLeader()) bg-blue-100 text-blue-800
                            @elseif($leaveRequest->isApproved()) bg-emerald-100 text-emerald-800
                            @elseif($leaveRequest->isRejected()) bg-rose-100 text-rose-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $leaveRequest->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Employee</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $leaveRequest->user->name }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Leave Type</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ ucfirst($leaveRequest->leave_type) . ' Leave' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Start Date</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">End Date</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Duration</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $leaveRequest->total_days }} days</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Address During Leave</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $leaveRequest->address_during_leave ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Emergency Contact</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $leaveRequest->emergency_contact ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Status</p>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full 
                                    @if($leaveRequest->isPending()) bg-yellow-100 text-yellow-800
                                    @elseif($leaveRequest->isApprovedByLeader()) bg-blue-100 text-blue-800
                                    @elseif($leaveRequest->isApproved()) bg-emerald-100 text-emerald-800
                                    @elseif($leaveRequest->isRejected()) bg-rose-100 text-rose-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $leaveRequest->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-medium text-slate-600 mb-2">Reason</h4>
                        <p class="text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                            {{ $leaveRequest->reason }}
                        </p>
                    </div>

                    @if($leaveRequest->attachment_path)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-slate-600 mb-2">Attachment</h4>
                            <a href="{{ Storage::url($leaveRequest->attachment_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 underline">
                                View Medical Certificate
                            </a>
                        </div>
                    @endif

                    @if($leaveRequest->rejection_note)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-slate-600 mb-2">Rejection Note</h4>
                            <p class="text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4">
                                {{ $leaveRequest->rejection_note }}
                            </p>
                        </div>
                    @endif>

                    <div class="flex items-center justify-end mt-4 sm:mt-6">
                        <a href="{{ route('leave-requests.index') }}" class="w-full sm:w-auto text-center bg-slate-500 hover:bg-slate-600 text-white font-medium py-2.5 px-4 sm:px-6 rounded-lg transition-all hover:-translate-y-0.5 text-sm sm:text-base">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>