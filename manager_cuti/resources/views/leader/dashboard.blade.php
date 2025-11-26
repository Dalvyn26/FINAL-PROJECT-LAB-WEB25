<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('Division Leader Dashboard') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Overview of your team's leave requests and activity</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-[#F8FAFC]" x-data="{ mounted: false }" x-init="mounted = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Leave Requests Card -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden">
                    <!-- Gradient highlight strip -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#4F46E5] to-[#6366F1]"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-indigo-50 shadow-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#4F46E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Leave Requests</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $totalRequests }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">All time</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending for Review Card -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden">
                    <!-- Gradient highlight strip -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-amber-50 shadow-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Pending for Review</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-amber-600 tracking-tight">{{ $pendingRequests }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">Awaiting approval</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('leader.leave-requests.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 sm:px-5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md">
                        Review Requests
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Who is on Leave This Week -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 mb-6 sm:mb-8 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Sedang Cuti Minggu Ini</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Team members currently on leave</p>
                    </div>
                </div>
                
                @if($onLeaveThisWeek->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($onLeaveThisWeek as $index => $request)
                            <div class="flex items-center gap-4 p-5 bg-white border border-[#E5E7EB] rounded-xl hover:shadow-md hover:border-[#4F46E5]/30 transition-all duration-300 hover:-translate-y-0.5"
                                 x-data="{ mounted: false }"
                                 x-init="setTimeout(() => mounted = true, {{ $index * 50 }})"
                                 x-show="mounted"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <div class="relative flex-shrink-0">
                                    <x-avatar :user="$request->user" classes="w-14 h-14" />
                                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $request->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full shadow-sm
                                            {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                            {{ ucfirst($request->leave_type) . ' Leave' }}
                                        </span>
                                        <span class="text-xs text-[#6B7280]">
                                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-100 mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-slate-900 mb-1">Semua anggota hadir</h4>
                        <p class="text-sm text-[#6B7280]">Tidak ada anggota divisi yang sedang cuti minggu ini.</p>
                    </div>
                @endif
            </div>

            <!-- Division Members -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Daftar Anggota Divisi</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Complete team member list</p>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto -mx-2">
                    <div class="inline-block min-w-full align-middle px-2">
                        <table class="min-w-full divide-y divide-[#E5E7EB]">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Quota</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                @forelse($members as $index => $member)
                                    <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group"
                                        x-data="{ mounted: false }"
                                        x-init="setTimeout(() => mounted = true, {{ $index * 30 }})"
                                        x-show="mounted"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="relative">
                                                    <x-avatar :user="$member" classes="w-12 h-12" />
                                                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 {{ $member->active_status ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white rounded-full"></div>
                                                </div>
                                                <div class="font-semibold text-slate-900">{{ $member->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-sm text-[#6B7280]">
                                            {{ $member->email }}
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                                {{ $member->leave_quota }} days
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                {{ $member->active_status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' }}">
                                                {{ $member->active_status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 mb-3">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-[#6B7280]">No members in your division.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($members as $member)
                        <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="relative">
                                    <x-avatar :user="$member" classes="w-12 h-12" />
                                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 {{ $member->active_status ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $member->name }}</h3>
                                    <p class="text-xs text-[#6B7280] truncate">{{ $member->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-[#E5E7EB]">
                                <div>
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Quota</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $member->leave_quota }} days</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Status</p>
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full shadow-sm {{ $member->active_status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' }}">
                                        {{ $member->active_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-[#E5E7EB] rounded-xl p-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-[#6B7280]">No members in your division.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
