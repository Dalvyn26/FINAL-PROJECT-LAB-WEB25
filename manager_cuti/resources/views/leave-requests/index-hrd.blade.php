<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Final Approval') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{
        selectedIds: [],
        selectAll: false,
        allIds: @json($leaveRequests->pluck('id')->toArray()).map(String),
        showBulkRejectModal: false,
        bulkRejectionNote: '',
        rejectModalOpen: false,
        rejectId: null,
        rejectNote: '',
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
        openRejectModal(id) {
            this.rejectId = id;
            this.rejectNote = ''; // Reset the note
            this.rejectModalOpen = true;
        },
        closeRejectModal() {
            this.rejectModalOpen = false;
            this.rejectId = null;
            this.rejectNote = '';
        }
    }" x-init="
        // Watch for changes in selectedIds to update selectAll state correctly
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
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 translate-y-2" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white border border-slate-200 rounded-xl shadow-lg p-4 z-10 w-full max-w-4xl flex justify-between items-center">
                <div class="text-slate-700 font-medium">
                    <span x-text="selectedIds.length"></span> item(s) selected
                </div>
                <div class="flex space-x-3">
                    <form method="POST" action="{{ route('hrd.leave-requests.bulk-update') }}" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" :name="'ids[]'" :value="id">
                        </template>
                        <button type="submit" 
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5"
                                @click="return confirm('Are you sure you want to approve ' + selectedIds.length + ' request(s)?')">
                            Approve Selected
                        </button>
                    </form>
                    <button @click="openBulkRejectModal()" 
                            class="bg-rose-600 hover:bg-rose-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                        Reject Selected
                    </button>
                </div>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <input type="checkbox"
                               x-model="selectAll"
                               @change="toggleSelectAll"
                               class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label class="ml-2 text-sm text-slate-600">Select All</label>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800">Leave Requests for Final Approval</h3>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('bulk_errors'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach(session('bulk_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($leaveRequests->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($leaveRequests as $request)
                            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all relative" 
                                 :class="selectedIds.includes('{{ $request->id }}') ? 'ring-2 ring-indigo-500 bg-indigo-50' : ''">
                                <!-- Selection Checkbox -->
                                <div class="absolute top-3 right-3">
                                    <input
                                        type="checkbox"
                                        value="{{ $request->id }}"
                                        class="request-checkbox rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        x-model="selectedIds">
                                </div>
                                
                                <!-- User Info -->
                                <div class="flex items-center mb-4">
                                    <x-avatar :user="$request->user" classes="w-12 h-12 mr-3" />
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-800">{{ $request->user->name }}</h4>
                                        <p class="text-xs text-slate-500">{{ $request->user->division ? $request->user->division->name : 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Leave Type Badge -->
                                <div class="mb-3">
                                    <span class="px-2 inline-flex text-xs font-bold rounded-full 
                                        {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($request->leave_type) . ' Leave' }}
                                    </span>
                                </div>

                                <!-- Leave Info -->
                                <div class="text-sm text-slate-600 mb-2">
                                    <div class="mb-1"><strong>Dates:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                    <div class="mb-1"><strong>Duration:</strong> {{ $request->total_days }} days</div>
                                    <div class="mb-1"><strong>Remaining Quota:</strong> <span class="font-medium text-slate-700">{{ $request->user->leave_quota - ($request->leave_type === 'annual' ? $request->total_days : 0) }} days</span></div>
                                </div>

                                <!-- Reason -->
                                <div class="mb-4">
                                    <p class="text-sm text-slate-600"><strong>Reason:</strong></p>
                                    <p class="text-sm text-slate-700">{{ Str::limit($request->reason, 80) }}</p>
                                </div>

                                <!-- Medical Certificate Link (if sick leave) -->
                                @if($request->leave_type === 'sick' && $request->attachment_path)
                                    <div class="mb-4">
                                        <a href="{{ Storage::url($request->attachment_path) }}" 
                                           target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            View Medical Certificate
                                        </a>
                                    </div>
                                @endif

                                <!-- Division Leader Approval Info (only for approved_by_leader status) -->
                                @if($request->status === 'approved_by_leader' && $request->approver)
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 mb-4 text-xs">
                                        <div class="font-medium text-slate-700">Verified By</div>
                                        <div class="text-slate-600 flex items-center">
                                            <span class="font-medium">{{ $request->approver->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>Date: {{ \Carbon\Carbon::parse($request->updated_at)->format('d M Y') }}</span>
                                        </div>
                                        @if($request->leader_note)
                                            <div class="mt-2">
                                                <div class="font-medium text-slate-700">Note:</div>
                                                <div class="text-slate-600">{{ $request->leader_note }}</div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div class="mb-4">
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full
                                        @switch($request->status)
                                            @case('approved_by_leader')
                                                bg-indigo-100 text-indigo-800
                                                @break
                                            @case('pending')
                                                bg-amber-100 text-amber-800
                                                @break
                                            @case('approved')
                                                bg-emerald-100 text-emerald-800
                                                @break
                                            @case('rejected')
                                                bg-rose-100 text-rose-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($request->status)
                                            @case('approved_by_leader')
                                                Approved by Leader
                                                @break
                                            @case('pending')
                                                Pending Approval
                                                @break
                                            @case('approved')
                                                Approved
                                                @break
                                            @case('rejected')
                                                Rejected
                                                @break
                                            @default
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        @endswitch
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 text-sm text-slate-500 italic">
                                    Select this request using the checkbox to approve/reject with bulk actions.
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No pending requests</h3>
                        <p class="mt-1 text-sm text-slate-500">There are no leave requests pending for final approval.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Individual Rejection Modal -->
        <div x-data="{ open: false, requestId: null }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-800" id="modal-title">
                                    Reject Leave Request
                                </h3>
                                <div class="mt-4">
                                    <form id="individualRejectionForm" method="POST">
                                        @csrf
                                        @method('POST')
                                        <label for="individual_rejection_note" class="block text-sm font-medium text-slate-700 mb-1">Rejection Reason *</label>
                                        <textarea name="rejection_note" id="individual_rejection_note" rows="4" required minlength="10" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                        <p class="mt-1 text-xs text-slate-500">Minimum 10 characters required</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button form="individualRejectionForm" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm" @click="if(document.getElementById('individual_rejection_note').value.length < 10) { alert('Rejection note must be at least 10 characters'); return false; }">
                            Reject Request
                        </button>
                        <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
                
                <div x-show="showBulkRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-800" id="modal-title">
                                    Bulk Reject Leave Requests
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-slate-600">
                                        Are you sure you want to reject <span x-text="selectedIds.length"></span> leave request(s)?
                                    </p>
                                    <form id="bulkRejectionForm" method="POST" action="{{ route('hrd.leave-requests.bulk-update') }}">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <template x-for="id in selectedIds" :key="id">
                                            <input type="hidden" :name="'ids[]'" :value="id">
                                        </template>
                                        <label for="bulk_rejection_note" class="block text-sm font-medium text-slate-700 mt-4">Rejection Reason *</label>
                                        <textarea name="rejection_note" id="bulk_rejection_note" rows="4" required x-model="bulkRejectionNote" minlength="10" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                        <p class="mt-1 text-xs text-slate-500">Minimum 10 characters required</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button form="bulkRejectionForm" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm" @click="if(bulkRejectionNote.length < 10) { alert('Rejection note must be at least 10 characters'); return false; }">
                            Reject Selected
                        </button>
                        <button @click="showBulkRejectModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Reject Modal -->
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
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-800" id="modal-title">
                                    Reject Leave Request
                                </h3>
                                <div class="mt-4">
                                    <form id="individualRejectionForm" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="rejection_note" :value="rejectNote" required>
                                        <label for="individual_rejection_note" class="block text-sm font-medium text-slate-700 mb-2">Rejection Reason *</label>
                                        <textarea
                                            id="individual_rejection_note"
                                            rows="4"
                                            required
                                            x-model="rejectNote"
                                            minlength="10"
                                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </textarea>
                                        <p class="mt-1 text-xs text-slate-500">Minimum 10 characters required</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            @click="if(rejectNote.length < 10) { alert('Rejection note must be at least 10 characters'); return false; };
                            document.getElementById('individualRejectionForm').action = `/hrd/leave-requests/${rejectId}/reject`;
                            document.getElementById('individualRejectionForm').submit();"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm"
                            x-bind:disabled="rejectNote.length < 10"
                            x-bind:class="rejectNote.length < 10 ? 'opacity-50 cursor-not-allowed' : ''">
                            Confirm Reject
                        </button>
                        <button @click="closeRejectModal"
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Handle form submission from modal
            document.addEventListener('alpine:init', () => {
                document.addEventListener('submit-form', async (e) => {
                    e.preventDefault();

                    const { action, note } = e.detail;

                    // Create a temporary form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfInput);

                    // Add method override
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'POST';
                    form.appendChild(methodInput);

                    // Add rejection note
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