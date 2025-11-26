<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('HRD Dashboard') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Overview of leave requests and team activity</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-[#F8FAFC]" x-data="{ 
        mounted: false,
        init() {
            this.mounted = true;
        }
    }" x-init="mounted = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8" 
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Leaves This Month -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1"
                     style="animation-delay: 0ms;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] shadow-lg shadow-indigo-500/20">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10m-10 4h10m4-16v16m-4-16v16m-4-16v16m-4-16v16"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Leave Requests</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $totalLeavesThisMonth }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Final Approvals -->
                <div class="group bg-gradient-to-br from-orange-500 to-amber-500 rounded-[20px] p-4 sm:p-6 text-white shadow-lg shadow-orange-500/20 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-1 hover:scale-[1.02]"
                     style="animation-delay: 100ms;">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-white/20 backdrop-blur-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-white/90 mb-1">Pending Final Approval</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-white tracking-tight">{{ $pendingFinalApprovals }}</p>
                                <p class="text-xs text-white/80 mt-1">Awaiting review</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('hrd.leave-requests.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 sm:px-5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        Review Pending Requests
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Current Month Leave Overview -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 mb-6 sm:mb-8 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Absensi Cuti Bulan Ini</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Employees currently on leave</p>
                    </div>
                </div>
                
                @if($onLeaveThisMonth->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto -mx-2">
                        <div class="inline-block min-w-full align-middle px-2">
                            <table class="min-w-full divide-y divide-[#E5E7EB]">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Employee</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Division</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Leave Dates</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Days</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                    @foreach($onLeaveThisMonth as $index => $request)
                                        <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group"
                                            x-data="{ mounted: false }"
                                            x-init="setTimeout(() => mounted = true, {{ $index * 50 }})"
                                            x-show="mounted"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-x-4"
                                            x-transition:enter-end="opacity-100 translate-x-0">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <x-avatar :user="$request->user" classes="w-12 h-12" />
                                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-slate-900">{{ $request->user->name }}</div>
                                                        <div class="text-xs text-[#6B7280] mt-0.5">{{ $request->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-[#6B7280]">
                                                {{ $request->user->division ? $request->user->division->name : 'N/A' }}
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-3">
                        @foreach($onLeaveThisMonth as $request)
                            <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="relative">
                                        <x-avatar :user="$request->user" classes="w-12 h-12" />
                                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $request->user->name }}</h3>
                                        <p class="text-xs text-[#6B7280] truncate">{{ $request->user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 pt-3 border-t border-[#E5E7EB]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Division:</span>
                                        <span class="text-xs font-medium text-slate-700">{{ $request->user->division ? $request->user->division->name : 'N/A' }}</span>
                                    </div>
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900 mb-1">Tidak ada karyawan cuti bulan ini</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Tidak ada karyawan yang sedang cuti pada periode ini.</p>
                    </div>
                @endif
            </div>

            <!-- Division Overview -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Overview Divisi</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Division structure and team members</p>
                    </div>
                </div>
                
                @if($divisions->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($divisions as $index => $division)
                            <div class="border border-[#E5E7EB] rounded-xl p-5 hover:shadow-md hover:border-[#4F46E5]/20 transition-all duration-300 hover:-translate-y-0.5 bg-white"
                                 x-data="{ mounted: false }"
                                 x-init="setTimeout(() => mounted = true, {{ $index * 100 }})"
                                 x-show="mounted"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <div class="flex flex-col space-y-3">
                                    <div class="flex items-start justify-between">
                                        <h4 class="font-bold text-slate-900 text-base">{{ $division->name }}</h4>
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                            {{ $division->users_count }} Staff
                                        </span>
                                    </div>
                                    <p class="text-sm text-[#6B7280] leading-relaxed">{{ $division->description ?: 'No description' }}</p>
                                    <div class="pt-2 border-t border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Leader:</p>
                                        @if($division->leader)
                                            <p class="text-sm font-semibold text-slate-700">{{ $division->leader->name }}</p>
                                        @else
                                            <p class="text-sm text-slate-400 italic">No leader assigned</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-[#6B7280]">No divisions created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
