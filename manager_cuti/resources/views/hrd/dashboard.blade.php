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
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8" 
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Leaves This Month -->
                <a href="{{ route('hrd.leave-summary.index', array_merge(request()->query(), ['status' => 'all', 'month' => now()->month, 'year' => now()->year])) }}" 
                   class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 cursor-pointer block"
                   style="animation-delay: 0ms;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] shadow-lg shadow-indigo-500/20">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10m-10 4h10m4-16v16m-4-16v16m-4-16v16m-4-16v16"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Requests</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $totalLeavesThisMonth }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This month</p>
                            </div>
                        </div>
                    </div>
                </a>

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
                                <h3 class="text-xs sm:text-sm font-medium text-white/90 mb-1">Pending Approval</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-white tracking-tight">{{ $pendingFinalApprovals }}</p>
                                <p class="text-xs text-white/80 mt-1">Awaiting review</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('hrd.leave-requests.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-xs sm:text-sm font-semibold py-2.5 px-4 sm:px-5 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        Review
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- Approved This Month -->
                <a href="{{ route('hrd.leave-summary.index', array_merge(request()->query(), ['status' => 'approved', 'month' => now()->month, 'year' => now()->year])) }}" 
                   class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 cursor-pointer block {{ request('status') === 'approved' && request('month') == now()->month && request('year') == now()->year ? 'ring-2 ring-emerald-500 border-emerald-500' : '' }}"
                   style="animation-delay: 200ms;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Approved</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-emerald-600 tracking-tight">{{ $approvedLeavesThisMonth }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This month</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Rejected This Month -->
                <a href="{{ route('hrd.leave-summary.index', array_merge(request()->query(), ['status' => 'rejected', 'month' => now()->month, 'year' => now()->year])) }}" 
                   class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 cursor-pointer block {{ request('status') === 'rejected' && request('month') == now()->month && request('year') == now()->year ? 'ring-2 ring-rose-500 border-rose-500' : '' }}"
                   style="animation-delay: 300ms;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 shadow-lg shadow-rose-500/20">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Rejected</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-rose-600 tracking-tight">{{ $rejectedLeavesThisMonth }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This month</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Daftar Karyawan yang Sedang Cuti Bulan Ini -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Karyawan yang Sedang Cuti Bulan Ini</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Daftar karyawan yang sedang mengambil cuti di bulan {{ \Carbon\Carbon::now()->format('F Y') }}</p>
                    </div>
                </div>
                
                @if($employeesOnLeave->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto -mx-2">
                        <div class="inline-block min-w-full align-middle px-2">
                            <table class="min-w-full divide-y divide-[#E5E7EB]">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Karyawan</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Divisi</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Tanggal Cuti</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Durasi</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Jenis Cuti</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                    @foreach($employeesOnLeave as $leave)
                                        <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <x-avatar :user="$leave->user" classes="w-12 h-12" />
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-slate-900">{{ $leave->user->username ?? $leave->user->name }}</div>
                                                        @if($leave->user->username && $leave->user->name)
                                                            <div class="text-xs text-[#6B7280] mt-0.5">{{ $leave->user->name }}</div>
                                                        @endif
                                                        <div class="text-xs text-[#6B7280] mt-0.5">{{ $leave->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-[#6B7280]">
                                                {{ $leave->user->division ? $leave->user->division->name : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-700">
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</div>
                                                <div class="text-xs text-[#6B7280]">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                                    {{ $leave->total_days }} hari
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                    {{ $leave->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                                    {{ ucfirst($leave->leave_type) . ' Leave' }}
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
                        @foreach($employeesOnLeave as $leave)
                            <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="relative">
                                        <x-avatar :user="$leave->user" classes="w-12 h-12" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $leave->user->username ?? $leave->user->name }}</h3>
                                        @if($leave->user->username && $leave->user->name)
                                            <p class="text-xs text-[#6B7280] mt-0.5 truncate">{{ $leave->user->name }}</p>
                                        @endif
                                        <p class="text-xs text-[#6B7280] truncate">{{ $leave->user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 pt-3 border-t border-[#E5E7EB]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Divisi:</span>
                                        <span class="text-xs font-medium text-slate-700">{{ $leave->user->division ? $leave->user->division->name : 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Tanggal:</span>
                                        <span class="text-xs text-slate-700">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Durasi:</span>
                                        <span class="text-xs font-semibold text-slate-700">{{ $leave->total_days }} hari</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-[#6B7280]">Jenis:</span>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full {{ $leave->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                            {{ ucfirst($leave->leave_type) . ' Leave' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-100 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-1">Tidak ada karyawan yang sedang cuti</h3>
                        <p class="text-sm text-[#6B7280]">Tidak ada karyawan yang sedang mengambil cuti di bulan ini.</p>
                    </div>
                @endif
            </div>

            <!-- Kalender Hari Libur Nasional -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm transition-all duration-300 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Kalender Hari Libur Nasional</h2>
                        <p class="text-slate-500 text-sm">Hari libur nasional Indonesia</p>
                    </div>
                </div>

                <div class="w-full rounded-xl overflow-hidden shadow-sm border border-slate-200">
                    <div class="relative" style="padding-top: 50%;">
                        <iframe 
                            class="absolute inset-0 w-full h-full"
                            src="https://calendar.google.com/calendar/embed?height=500&wkst=1&bgcolor=%23FFFFFF&ctz=Asia%2FMakassar&src=aWQuaW5kb25lc2lhbiNob2xpZGF5QGdyb3VwLnYuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%237986CB"
                            frameborder="0"
                            scrolling="no">
                        </iframe>
                    </div>
                </div>
            </div>


            <!-- Division Overview -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-400"
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
