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
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Requests</h3>
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
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1"
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
                </div>

                <!-- Rejected This Month -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1"
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
                </div>
            </div>

            <!-- Kalender Hari Libur Nasional -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mt-6 transition-all duration-300 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
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
