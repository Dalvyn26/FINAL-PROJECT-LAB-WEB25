<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('User Dashboard') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Overview of your leave quota and recent requests</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-[#F8FAFC]" x-data="{ mounted: false }" x-init="mounted = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Card 1: Remaining Annual Leave (Main Highlight) -->
                <div class="group bg-gradient-to-br from-[#4F46E5] via-[#6366F1] to-[#A855F7] rounded-[22px] p-6 sm:p-8 text-white shadow-xl shadow-indigo-500/20 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-1 relative overflow-hidden sm:col-span-2 lg:col-span-1"
                     style="animation-delay: 0ms;">
                    <!-- Subtle glow effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4 sm:mb-6">
                            <div class="p-3 sm:p-4 rounded-2xl bg-white/20 backdrop-blur-sm shadow-lg">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-white/90 mb-2">Remaining Annual Leave</h3>
                            <p class="text-4xl sm:text-5xl font-bold tracking-tight mb-1">{{ $remainingQuota }}</p>
                            <p class="text-xs sm:text-sm text-white/80">days available</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Leave Statistics -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1"
                     style="animation-delay: 100ms;">
                    <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="p-2.5 sm:p-3 rounded-xl bg-blue-50 shadow-sm">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-700">Leave Statistics</h3>
                    </div>
                    <div class="space-y-2 sm:space-y-3 pt-2 sm:pt-3 border-t border-[#E5E7EB]">
                        <div>
                            <p class="text-xs font-medium text-[#6B7280] mb-1">Total Requests</p>
                            <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $totalLeaves }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#6B7280] mb-1">Sick Leave</p>
                            <p class="text-lg sm:text-xl font-semibold text-slate-700">{{ $totalSickLeave }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Division Information -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1"
                     style="animation-delay: 200ms;">
                    <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="p-2.5 sm:p-3 rounded-xl bg-emerald-50 shadow-sm">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-700">Division Information</h3>
                    </div>
                    <div class="space-y-2 sm:space-y-3 pt-2 sm:pt-3 border-t border-[#E5E7EB]">
                        <div>
                            <p class="text-xs font-medium text-[#6B7280] mb-1">Division</p>
                            <p class="text-sm sm:text-base font-semibold text-slate-900">
                                @if($user->division)
                                    {{ $user->division->name }}
                                @else
                                    <span class="italic text-slate-400">Belum ada divisi</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-[#6B7280] mb-1">Leader</p>
                            <p class="text-sm sm:text-base font-semibold text-slate-900">
                                @if($user->division && $user->division->leader)
                                    {{ $user->division->leader->name }}
                                @else
                                    <span class="italic text-slate-400">Tidak ada ketua</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leave Requests Table -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[22px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Recent Leave Requests</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Your latest leave request submissions</p>
                    </div>
                </div>
                
                @if($user->leaveRequests->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto -mx-2">
                        <div class="inline-block min-w-full align-middle px-2">
                            <table class="min-w-full divide-y divide-[#E5E7EB]">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Dates</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Days</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                    @foreach($user->leaveRequests()->latest()->take(5)->get() as $index => $request)
                                        <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group"
                                            x-data="{ mounted: false }"
                                            x-init="setTimeout(() => mounted = true, {{ $index * 50 }})"
                                            x-show="mounted"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-x-4"
                                            x-transition:enter-end="opacity-100 translate-x-0">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                    {{ $request->isAnnualLeave() ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                                    {{ ucfirst($request->leave_type) . ' Leave' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</div>
                                                <div class="text-xs text-[#6B7280] mt-0.5">{{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                                    {{ $request->total_days }} days
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm transition-all duration-200 hover:scale-105
                                                    @if($request->isPending()) bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                                    @elseif($request->isApprovedByLeader()) bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200
                                                    @elseif($request->isApproved()) bg-blue-50 text-blue-700 ring-1 ring-blue-200
                                                    @elseif($request->isRejected()) bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                                    @else bg-slate-50 text-slate-700 ring-1 ring-slate-200
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2 text-sm text-[#6B7280]">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-3">
                        @foreach($user->leaveRequests()->latest()->take(5)->get() as $request)
                            <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full shadow-sm mb-2
                                            {{ $request->isAnnualLeave() ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                            {{ ucfirst($request->leave_type) . ' Leave' }}
                                        </span>
                                        <div class="text-sm font-medium text-slate-900">{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                        <div class="text-xs text-[#6B7280] mt-1">{{ $request->total_days }} days</div>
                                    </div>
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full shadow-sm
                                        @if($request->isPending()) bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                        @elseif($request->isApprovedByLeader()) bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200
                                        @elseif($request->isApproved()) bg-blue-50 text-blue-700 ring-1 ring-blue-200
                                        @elseif($request->isRejected()) bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                        @else bg-slate-50 text-slate-700 ring-1 ring-slate-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-[#6B7280] pt-3 border-t border-[#E5E7EB]">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($user->leaveRequests()->count() > 5)
                        <div class="mt-6 text-center pt-4 border-t border-[#E5E7EB]">
                            <a href="{{ route('leave-requests.index') }}" 
                               class="inline-flex items-center gap-2 text-[#4F46E5] hover:text-[#6366F1] font-semibold text-sm transition-all duration-200 hover:underline group">
                                View All Requests
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-1">No leave requests</h3>
                        <p class="text-sm text-[#6B7280] mb-6">You haven't made any leave requests yet.</p>
                        <div>
                            <a href="{{ route('leave-requests.create') }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4F46E5] to-[#6366F1] hover:from-[#4338CA] hover:to-[#4F46E5] text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Request Leave
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
