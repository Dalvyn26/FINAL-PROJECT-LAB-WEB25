<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Team Approval') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 transition-all">
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
                            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
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
                                        @click="openRejectionModal({{ $request->id }})" 
                                        class="bg-rose-100 hover:bg-rose-200 text-rose-700 font-medium w-full py-2 px-4 rounded-lg transition-all">
                                        Reject
                                    </button>
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
                        <p class="mt-1 text-sm text-slate-500">There are no leave requests pending for your approval.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for rejection -->
    <div x-data="{ open: false, requestId: null }" x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-semibold text-slate-800">Reject Leave Request</h3>
                    <button @click="open = false" class="text-slate-500 hover:text-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="rejectionForm" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="mt-4">
                        <label for="rejection_note" class="block text-sm font-medium text-slate-700 mb-1">Rejection Reason *</label>
                        <textarea name="rejection_note" id="rejection_note" rows="4" required class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <div class="items-center gap-2 mt-6">
                        <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700">Reject Request</button>
                        <button type="button" @click="open = false" class="px-4 py-2 bg-slate-300 text-slate-800 rounded-lg hover:bg-slate-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectionModal(requestId) {
            document.getElementById('rejectionForm').action = `/leader/leave-requests/${requestId}/reject`;
            document.querySelector('[x-data]').__x.data.open = true;
            document.querySelector('[x-data]').__x.data.requestId = requestId;
        }
    </script>
</x-app-layout>