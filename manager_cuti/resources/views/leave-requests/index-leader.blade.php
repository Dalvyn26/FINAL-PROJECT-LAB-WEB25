<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Team Approval') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Pending Leave Requests from Your Division</h3>
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

                @if($leaveRequests->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($leaveRequests as $request)
                            <div x-data="{ showRejectModal: false }" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-700 font-medium">{{ strtoupper(substr($request->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-semibold text-slate-800">{{ $request->user->name }}</h4>
                                        <p class="text-sm text-slate-500">{{ $request->user->email }}</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <span class="px-2 inline-flex text-xs font-bold rounded-full
                                        {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($request->leave_type) . ' Leave' }}
                                    </span>
                                </div>

                                <div class="text-sm text-slate-600 mb-2">
                                    <div><strong>Dates:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                    <div><strong>Duration:</strong> {{ $request->total_days }} days</div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-slate-600"><strong>Reason:</strong></p>
                                    <p class="text-sm text-slate-700">{{ Str::limit($request->reason, 100) }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <form action="{{ route('leader.leave-requests.approve-by-leader', $request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium w-full py-2 px-4 rounded-lg transition-all">
                                            Approve
                                        </button>
                                    </form>

                                    <button
                                        @click="showRejectModal = true"
                                        class="bg-rose-100 hover:bg-rose-200 text-rose-700 font-medium w-full py-2 px-4 rounded-lg transition-all">
                                        Reject
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div x-show="showRejectModal"
                                     x-cloak
                                     class="fixed inset-0 z-50 overflow-y-auto"
                                     aria-labelledby="modal-title"
                                     role="dialog"
                                     aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showRejectModal"
                                             x-transition:enter="ease-out duration-300"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="ease-in duration-200"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                             @click="showRejectModal = false"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        <div x-show="showRejectModal"
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
                                                            <label for="rejection_note_{{ $request->id }}" class="block text-sm font-medium text-slate-700 mb-1">Rejection Reason *</label>
                                                            <form id="rejectionForm_{{ $request->id }}" method="POST" action="{{ route('leader.leave-requests.reject', $request) }}">
                                                                @csrf
                                                                @method('POST')
                                                                <textarea name="rejection_note" id="rejection_note_{{ $request->id }}" rows="4" required
                                                                          class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('rejection_note') }}</textarea>
                                                            </form>

                                                            @error('rejection_note')
                                                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button form="rejectionForm_{{ $request->id }}" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Confirm Reject
                                                </button>
                                                <button @click="showRejectModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Reject Modal -->
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
                        <p class="mt-1 text-sm text-slate-500">There are no leave requests pending for your approval.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>