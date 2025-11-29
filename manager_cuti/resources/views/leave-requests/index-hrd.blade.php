<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('Final Approval') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Review and approve leave requests from your team</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC]" x-data="{
        selectedIds: [],
        selectAll: false,
        allIds: @json($leaveRequests->pluck('id')->toArray()).map(String),
        showBulkRejectModal: false,
        bulkRejectionNote: '',
        showBulkApproveModal: false,
        bulkHrdNote: '',
        rejectModalOpen: false,
        rejectId: null,
        rejectNote: '',
        mounted: false,
        toggleSelectAll() {
            this.selectedIds = this.selectAll ? this.allIds.slice() : [];
        },
        updateSelectAllState() {
            this.selectAll = this.selectedIds.length === this.allIds.length && this.allIds.length > 0;
        },
        openBulkRejectModal() {
            if (this.selectedIds.length > 0) {
                this.showBulkRejectModal = true;
                this.bulkRejectionNote = '';
            }
        },
        openBulkApproveModal() {
            if (this.selectedIds.length > 0) {
                this.showBulkApproveModal = true;
                this.bulkHrdNote = '';
            }
        },
        openRejectModal(id) {
            this.rejectId = id;
            this.rejectNote = '';
            this.rejectModalOpen = true;
        },
        closeRejectModal() {
            this.rejectModalOpen = false;
            this.rejectId = null;
            this.rejectNote = '';
        },
        init() {
            this.mounted = true;
        }
    }" x-init="
        mounted = true;
        $nextTick(() => {
            $watch('selectedIds', (newVal) => {
                if (newVal.length === this.allIds.length && this.allIds.length > 0) {
                    this.selectAll = true;
                } else {
                    this.selectAll = false;
                }
            });
        });
    ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Bulk Action Toolbar -->
            <div x-show="selectedIds.length > 0" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="fixed bottom-4 sm:bottom-6 left-4 right-4 sm:left-1/2 sm:right-auto sm:transform sm:-translate-x-1/2 bg-white border border-[#E5E7EB] rounded-2xl shadow-xl p-4 sm:p-5 z-50 sm:w-full sm:max-w-4xl flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm" x-text="selectedIds.length"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-slate-900 truncate" x-text="selectedIds.length + ' item(s) selected'"></div>
                        <div class="text-xs text-[#6B7280] hidden sm:block">Select actions below</div>
                    </div>
                </div>
                <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                    <button @click="openBulkApproveModal()" 
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-2.5 px-4 sm:px-5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md text-sm sm:text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden sm:inline">Approve Selected</span>
                        <span class="sm:hidden">Approve</span>
                    </button>
                    <button @click="openBulkRejectModal()" 
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-semibold py-2.5 px-4 sm:px-5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md text-sm sm:text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Reject Selected</span>
                        <span class="sm:hidden">Reject</span>
                    </button>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 pb-4 border-b border-[#E5E7EB]">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <input type="checkbox"
                                   x-model="selectAll"
                                   @change="toggleSelectAll"
                                   class="w-5 h-5 rounded-lg border-[#E5E7EB] text-[#4F46E5] shadow-sm focus:ring-[#4F46E5] focus:ring-2 cursor-pointer transition-all">
                            <label class="text-sm font-semibold text-slate-700 cursor-pointer">Select All</label>
                        </div>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">Leave Requests for Final Approval</h3>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('bulk_errors'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach(session('bulk_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($leaveRequests->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($leaveRequests as $index => $request)
                            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 sm:p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 relative group"
                                 :class="selectedIds.includes('{{ $request->id }}') ? 'ring-2 ring-[#4F46E5] bg-indigo-50/50 border-[#4F46E5]' : 'hover:border-[#4F46E5]/30'"
                                 x-data="{ mounted: false }"
                                 x-init="setTimeout(() => mounted = true, {{ $index * 50 }})"
                                 x-show="mounted"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <!-- Selection Checkbox -->
                                <div class="absolute top-4 right-4">
                                    <input
                                        type="checkbox"
                                        value="{{ $request->id }}"
                                        class="w-5 h-5 rounded-lg border-[#E5E7EB] text-[#4F46E5] shadow-sm focus:ring-[#4F46E5] focus:ring-2 cursor-pointer transition-all hover:scale-110"
                                        x-model="selectedIds">
                                </div>
                                
                                <!-- User Info -->
                                <div class="flex items-center mb-5 pr-8">
                                    <div class="relative mr-3">
                                        <x-avatar :user="$request->user" classes="w-14 h-14" />
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-semibold text-slate-900 truncate">{{ $request->user->username ?? $request->user->name }}</h4>
                                        @if($request->user->username && $request->user->name)
                                            <p class="text-xs text-[#6B7280] mt-0.5">{{ $request->user->name }}</p>
                                        @endif
                                        <p class="text-xs text-[#6B7280] mt-0.5">{{ $request->user->division ? $request->user->division->name : 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Leave Type Badge -->
                                <div class="mb-4">
                                    <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                        {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                        {{ ucfirst($request->leave_type) . ' Leave' }}
                                    </span>
                                </div>

                                <!-- Leave Info -->
                                <div class="space-y-2 mb-4 text-sm">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-[#6B7280] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-[#6B7280]">Dates: </span>
                                            <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#6B7280] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[#6B7280]">Duration: </span>
                                        <span class="font-semibold text-slate-900">{{ $request->total_days }} days</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#6B7280] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[#6B7280]">Remaining Quota: </span>
                                        <span class="font-semibold text-emerald-600">{{ $request->user->leave_quota - ($request->leave_type === 'annual' ? $request->total_days : 0) }} days</span>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="mb-4 p-3 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB] overflow-hidden" 
                                     x-data="{ 
                                         reason: @js($request->reason),
                                         maxLength: 60,
                                         isExpanded: false,
                                         get displayReason() {
                                             if (!this.reason) return '';
                                             if (this.isExpanded || this.reason.length <= this.maxLength) {
                                                 return this.reason;
                                             }
                                             return this.reason.substring(0, this.maxLength) + '...';
                                         },
                                         get shouldShowToggle() {
                                             return this.reason && this.reason.length > this.maxLength;
                                         }
                                     }">
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1.5">Reason:</p>
                                    <div class="overflow-hidden">
                                        <p class="text-sm text-slate-700 leading-relaxed break-words overflow-wrap-anywhere" 
                                           :class="!isExpanded && reason && reason.length > maxLength ? 'line-clamp-3' : ''"
                                           style="word-break: break-word; overflow-wrap: break-word; max-width: 100%;"
                                           x-text="displayReason"></p>
                                    </div>
                                    <button 
                                        x-show="shouldShowToggle"
                                        @click="isExpanded = !isExpanded"
                                        class="mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors underline"
                                        x-text="isExpanded ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya'">
                                    </button>
                                </div>

                                <!-- Medical Certificate Link (if sick leave) -->
                                @if($request->leave_type === 'sick' && $request->attachment_path)
                                    <div class="mb-4">
                                        <a href="{{ Storage::url($request->attachment_path) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-2 text-[#4F46E5] hover:text-[#6366F1] text-sm font-medium transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            View Medical Certificate
                                        </a>
                                    </div>
                                @endif

            
                                @if($request->status === 'approved_by_leader' && $request->approver)
                                    <div class="bg-indigo-50/50 border border-indigo-200 rounded-xl p-4 mb-4">
                                        <div class="flex items-start gap-2 mb-2">
                                            <svg class="w-4 h-4 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-xs font-semibold text-indigo-900 mb-1">Approved By Leader</div>
                                                <div class="text-xs text-indigo-700">
                                                    <span class="font-medium">{{ $request->approver->name }}</span>
                                                    <span class="mx-1.5">•</span>
                                                    <span>{{ \Carbon\Carbon::parse($request->updated_at)->format('d M Y, H:i') }}</span>
                                                </div>
                                                @if($request->leader_note)
                                                    <div class="mt-2 pt-2 border-t border-indigo-200">
                                                        <div class="text-xs font-semibold text-indigo-900 mb-1">Approval Note:</div>
                                                        <div class="text-xs text-indigo-700">{{ $request->leader_note }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-1">No pending requests</h3>
                        <p class="text-sm text-[#6B7280]">There are no leave requests pending for final approval.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Individual Rejection Modal -->
        <div x-show="rejectModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="rejectModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="closeRejectModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="rejectModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-4 pt-4 pb-4 sm:px-6 sm:pt-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-semibold text-slate-900" id="modal-title">
                                    Reject Leave Request
                                </h3>
                                <div class="mt-4">
                                    <form id="individualRejectionForm" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="rejection_note" :value="rejectNote" required>
                                        <label for="individual_rejection_note" class="block text-sm font-semibold text-slate-700 mb-2">Rejection Reason *</label>
                                        <textarea
                                            id="individual_rejection_note"
                                            rows="4"
                                            required
                                            x-model="rejectNote"
                                            minlength="10"
                                            class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 resize-none"></textarea>
                                        <p class="mt-2 text-xs text-[#6B7280]">Minimum 10 characters required</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#F8FAFC] px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button
                            @click="if(rejectNote.length < 10) { alert('Rejection note must be at least 10 characters'); return false; };
                            document.getElementById('individualRejectionForm').action = `/hrd/leave-requests/${rejectId}/reject`;
                            document.getElementById('individualRejectionForm').submit();"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-sm sm:text-base font-semibold text-white hover:from-rose-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all duration-300 hover:scale-105 sm:ml-3"
                            x-bind:disabled="rejectNote.length < 10"
                            x-bind:class="rejectNote.length < 10 ? 'opacity-50 cursor-not-allowed' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Confirm Reject
                        </button>
                        <button @click="closeRejectModal"
                                type="button"
                                class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-[#E5E7EB] shadow-sm px-4 py-2.5 bg-white text-sm sm:text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4F46E5] transition-all duration-200 sm:ml-3">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Rejection Modal -->
        <div x-show="showBulkRejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showBulkRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showBulkRejectModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showBulkRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-4 pt-4 pb-4 sm:px-6 sm:pt-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-semibold text-slate-900" id="modal-title">
                                    Bulk Reject Leave Requests
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-[#6B7280] mb-4">
                                        Are you sure you want to reject <span class="font-semibold text-slate-900" x-text="selectedIds.length"></span> leave request(s)?
                                    </p>
                                    <form id="bulkRejectionForm" method="POST" action="{{ route('hrd.leave-requests.bulk-update') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <template x-for="id in selectedIds" :key="id">
                                            <input type="hidden" :name="'ids[]'" :value="id">
                                        </template>
                                        <label for="bulk_rejection_note" class="block text-sm font-semibold text-slate-700 mb-2">Rejection Reason *</label>
                                        <textarea name="rejection_note" id="bulk_rejection_note" rows="4" required x-model="bulkRejectionNote" minlength="10" class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 resize-none"></textarea>
                                        <p class="mt-2 text-xs text-[#6B7280]">Minimum 10 characters required</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#F8FAFC] px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button form="bulkRejectionForm" type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-sm sm:text-base font-semibold text-white hover:from-rose-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all duration-300 hover:scale-105 sm:ml-3" @click="if(bulkRejectionNote.length < 10) { alert('Rejection note must be at least 10 characters'); return false; }">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject Selected
                        </button>
                        <button @click="showBulkRejectModal = false" type="button" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-[#E5E7EB] shadow-sm px-4 py-2.5 bg-white text-sm sm:text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4F46E5] transition-all duration-200 sm:ml-3">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Approval Modal -->
        <div x-show="showBulkApproveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showBulkApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showBulkApproveModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showBulkApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-4 pt-4 pb-4 sm:px-6 sm:pt-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-semibold text-slate-900" id="modal-title">
                                    Approve Leave Requests
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-[#6B7280] mb-4">
                                        Are you sure you want to approve <span class="font-semibold text-slate-900" x-text="selectedIds.length"></span> leave request(s)?
                                    </p>
                                    <form id="bulkApproveForm" method="POST" action="{{ route('hrd.leave-requests.bulk-update') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <template x-for="id in selectedIds" :key="id">
                                            <input type="hidden" :name="'ids[]'" :value="id">
                                        </template>
                                        <label for="bulk_hrd_note" class="block text-sm font-semibold text-slate-700 mb-2">
                                            Catatan <span class="text-slate-500 font-normal">(Opsional)</span>
                                        </label>
                                        <textarea name="hrd_note" id="bulk_hrd_note" rows="4" x-model="bulkHrdNote" maxlength="500" class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 resize-none"></textarea>
                                        <p class="mt-2 text-xs text-[#6B7280]">Opsional: Maksimal 500 karakter</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#F8FAFC] px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button form="bulkApproveForm" type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-sm sm:text-base font-semibold text-white hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 hover:scale-105 sm:ml-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve Selected
                        </button>
                        <button @click="showBulkApproveModal = false" type="button" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-[#E5E7EB] shadow-sm px-4 py-2.5 bg-white text-sm sm:text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4F46E5] transition-all duration-200 sm:ml-3">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                document.addEventListener('submit-form', async (e) => {
                    e.preventDefault();
                    const { action, note } = e.detail;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfInput);
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'POST';
                    form.appendChild(methodInput);
                    const noteInput = document.createElement('input');
                    noteInput.type = 'hidden';
                    noteInput.name = 'rejection_note';
                    noteInput.value = note;
                    form.appendChild(noteInput);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        </script>
    </div>
</x-app-layout>
