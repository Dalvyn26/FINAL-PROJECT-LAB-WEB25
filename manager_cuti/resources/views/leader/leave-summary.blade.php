<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('Leave Summary') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Detailed leave reports for {{ $division->name }} division</p>
            </div>
            <a href="{{ route('leader.dashboard') }}" 
               class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-[#F8FAFC]" x-data="{ mounted: false }" x-init="mounted = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Requests -->
                <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Requests</h3>
                            <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $totalRequests }}</p>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Approved</h3>
                            <p class="text-3xl sm:text-4xl font-bold text-emerald-600 tracking-tight">{{ $approvedRequests }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 shadow-lg shadow-amber-500/20">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Pending</h3>
                            <p class="text-3xl sm:text-4xl font-bold text-amber-600 tracking-tight">{{ $pendingRequests }}</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 shadow-lg shadow-rose-500/20">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Rejected</h3>
                            <p class="text-3xl sm:text-4xl font-bold text-rose-600 tracking-tight">{{ $rejectedRequests }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-100"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <form method="GET" action="{{ route('leader.leave-summary.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-[#6B7280] mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved_by_leader" {{ $status === 'approved_by_leader' ? 'selected' : '' }}>Approved by Leader</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Month Filter -->
                    <div>
                        <label for="month" class="block text-xs font-semibold text-[#6B7280] mb-2">Month</label>
                        <select name="month" id="month" class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label for="year" class="block text-xs font-semibold text-[#6B7280] mb-2">Year</label>
                        <select name="year" id="year" class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @for($y = now()->year; $y >= now()->year - 2; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="sm:col-span-2 lg:col-span-4 flex items-end">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#4F46E5] to-[#6366F1] hover:from-[#4338CA] hover:to-[#4F46E5] text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Leave Requests Table -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Leave Requests - {{ $division->name }}</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Filtered leave requests list for division members</p>
                    </div>
                </div>
                
                @if($leaveRequests->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto -mx-2">
                        <div class="inline-block min-w-full align-middle px-2">
                            <table class="min-w-full divide-y divide-[#E5E7EB]">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Employee</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Leave Dates</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Days</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                    @foreach($leaveRequests as $index => $request)
                                        <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <x-avatar :user="$request->user" classes="w-12 h-12" />
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-slate-900">{{ $request->user->name }}</div>
                                                        <div class="text-xs text-[#6B7280] mt-0.5">{{ $request->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-700">
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</div>
                                                <div class="text-xs text-[#6B7280]">{{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                                    {{ $request->total_days }} days
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                    {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                                    {{ ucfirst($request->leave_type) . ' Leave' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                    @if($request->isPending()) bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                                    @elseif($request->isApprovedByLeader()) bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200
                                                    @elseif($request->isApproved()) bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                                    @elseif($request->isRejected()) bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                                    @else bg-slate-50 text-slate-700 ring-1 ring-slate-200
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-[#6B7280]">
                                                {{ \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-3">
                        @foreach($leaveRequests as $request)
                            <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="relative">
                                        <x-avatar :user="$request->user" classes="w-12 h-12" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $request->user->name }}</h3>
                                        <p class="text-xs text-[#6B7280] truncate">{{ $request->user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 pt-3 border-t border-[#E5E7EB]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Dates:</span>
                                        <span class="text-xs text-slate-700">{{ \Carbon\Carbon::parse($request->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Days:</span>
                                        <span class="text-xs font-semibold text-slate-700">{{ $request->total_days }} days</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Type:</span>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                            {{ ucfirst($request->leave_type) . ' Leave' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Status:</span>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full
                                            @if($request->isPending()) bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                            @elseif($request->isApprovedByLeader()) bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200
                                            @elseif($request->isApproved()) bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                            @elseif($request->isRejected()) bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                            @else bg-slate-50 text-slate-700 ring-1 ring-slate-200
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-1">No leave requests found</h3>
                        <p class="text-sm text-[#6B7280]">Try adjusting your filters to see more results.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

