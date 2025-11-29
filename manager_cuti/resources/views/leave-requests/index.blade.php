<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('My Leave History') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">View and manage your leave request history</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC]" 
         x-data="leaveDetailModal()" 
         @keydown.escape.window="showModal = false">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Cuti Tahun Ini -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 0ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-blue-50 shadow-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Pengajuan Cuti</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $totalLeavesThisYear }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This year</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisa Kuota -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 100ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-emerald-50 shadow-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Sisa Kuota</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ Auth::user()->leave_quota }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">Remaining days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Cuti Sakit -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 200ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 rounded-2xl bg-amber-50 shadow-sm">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-medium text-[#6B7280] mb-1">Total Cuti Sakit</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">{{ $sickLeavesThisYear }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This year</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline / List Riwayat -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1">Riwayat Pengajuan Cuti</h3>
                        <p class="text-xs sm:text-sm text-[#6B7280]">Complete history of your leave requests</p>
                    </div>
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

                @if($leaveRequests->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto -mx-2">
                        <div class="inline-block min-w-full align-middle px-2">
                            <table class="min-w-full divide-y divide-[#E5E7EB]">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Jenis Cuti</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Durasi</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Alasan</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#E5E7EB]">
                                    @foreach($leaveRequests as $index => $request)
                                        <tr class="hover:bg-[#F8FAFC] transition-all duration-200 hover:shadow-sm group"
                                            x-data="{ mounted: false }"
                                            x-init="setTimeout(() => mounted = true, {{ $index * 30 }})"
                                            x-show="mounted"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-x-4"
                                            x-transition:enter-end="opacity-100 translate-x-0">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                    {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                                    {{ ucfirst($request->leave_type) . ' Leave' }}
                                                </span>
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
                                            <td class="px-6 py-5 text-sm text-slate-600">
                                                <div class="max-w-[200px] overflow-hidden">
                                                    <p class="text-sm text-slate-600 break-words overflow-wrap-anywhere line-clamp-2" style="word-break: break-word; overflow-wrap: break-word;">
                                                        {{ Str::limit($request->reason, 40) }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center justify-start">
                                                    <span class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full border transition-all
                                                        @if($request->isPending()) 
                                                            bg-amber-50 text-amber-700 border-amber-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                                        @elseif($request->isApprovedByLeader()) 
                                                            bg-indigo-50 text-indigo-700 border-indigo-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                                        @elseif($request->isApproved()) 
                                                            bg-emerald-50 text-emerald-700 border-emerald-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                                        @elseif($request->isRejected()) 
                                                            bg-rose-50 text-rose-700 border-rose-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center justify-start gap-2">
                                                    <!-- View Detail Icon -->
                                                    <button 
                                                        onclick="window.openLeaveDetailModal({{ $request->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer"
                                                        title="View Detail">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Cancel Button (if pending) -->
                                                    @if($request->user_id === auth()->id() && $request->isPending())
                                                        <form action="{{ route('leave-requests.cancel', $request) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center justify-center w-8 h-8 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer" 
                                                                    title="Cancel Request"
                                                                    onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Download PDF Icon (if approved) -->
                                                    @if($request->status === 'approved')
                                                        <a href="{{ route('leave-requests.download-pdf', $request) }}" 
                                                           class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer"
                                                           title="Download Surat Cuti (PDF)">
                                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4">
                        @foreach($leaveRequests as $index => $request)
                            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-300"
                                 x-data="{ mounted: false }"
                                 x-init="setTimeout(() => mounted = true, {{ $index * 30 }})"
                                 x-show="mounted"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm mb-2
                                            {{ $request->isAnnualLeave() ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                                            {{ ucfirst($request->leave_type) . ' Leave' }}
                                        </span>
                                        <div class="mt-2">
                                            <div class="text-sm font-medium text-slate-900">{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</div>
                                            <div class="text-xs text-[#6B7280] mt-1">{{ $request->total_days }} days</div>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 inline-flex items-center text-xs font-semibold rounded-full border transition-all
                                        @if($request->isPending()) 
                                            bg-amber-50 text-amber-700 border-amber-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                        @elseif($request->isApprovedByLeader()) 
                                            bg-indigo-50 text-indigo-700 border-indigo-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                        @elseif($request->isApproved()) 
                                            bg-emerald-50 text-emerald-700 border-emerald-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                        @elseif($request->isRejected()) 
                                            bg-rose-50 text-rose-700 border-rose-200/60 shadow-[0_1px_2px_0_rgba(0,0,0,0.05)]
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Alasan:</p>
                                    <div class="overflow-hidden">
                                        <p class="text-sm text-slate-700 break-words overflow-wrap-anywhere" style="word-break: break-word; overflow-wrap: break-word; max-width: 100%;">
                                            {{ Str::limit($request->reason, 60) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-3 pt-3 border-t border-[#E5E7EB]">
                                    <div class="flex items-center gap-2">
                                        <!-- View Detail Icon -->
                                        <button 
                                            onclick="window.openLeaveDetailModal({{ $request->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer"
                                            title="View Detail">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Cancel Icon (if pending) -->
                                        @if($request->user_id === auth()->id() && $request->isPending())
                                            <form action="{{ route('leave-requests.cancel', $request) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer" 
                                                        title="Cancel Request"
                                                        onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Download PDF Icon (if approved) -->
                                        @if($request->status === 'approved')
                                            <a href="{{ route('leave-requests.download-pdf', $request) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-105 cursor-pointer"
                                               title="Download Surat Cuti (PDF)">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
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
                        <h3 class="text-base font-semibold text-slate-900 mb-1">No leave history</h3>
                        <p class="text-sm text-[#6B7280]">You haven't made any leave requests yet.</p>
                    </div>
                @endif
            </div>

            <!-- Modal Detail Cuti -->
            <div 
                x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;"
                @click.self="closeModal()">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
        
        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden"
                @click.stop>
                
                <!-- Loading State -->
                <div x-show="loading" class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 mb-4">
                        <svg class="w-8 h-8 text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium">Memuat detail pengajuan...</p>
                </div>

                <!-- Content -->
                <div x-show="!loading && detailData" x-cloak style="display: none;">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Detail Pengajuan Cuti</h2>
                                    <p class="text-sm text-indigo-100 mt-0.5" x-text="detailData && detailData.pengajuan ? detailData.pengajuan.leave_type_label : ''"></p>
                                </div>
                            </div>
                            <button 
                                @click="closeModal()"
                                class="p-2 text-white hover:bg-white/20 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                        <!-- Section Info -->
                        <div class="bg-slate-50 rounded-2xl p-6 mb-6 border border-slate-200 w-full max-w-full overflow-hidden">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Data Pengajuan Cuti</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Tanggal</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pengajuan ? (detailData.pengajuan.start_date + ' - ' + detailData.pengajuan.end_date) : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Durasi</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pengajuan ? (detailData.pengajuan.total_days + ' hari') : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Jenis Cuti</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pengajuan ? detailData.pengajuan.leave_type_label : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Lampiran</label>
                                    <template x-if="detailData && detailData.pengajuan && detailData.pengajuan.attachment_url">
                                        <a :href="detailData.pengajuan.attachment_url" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Lampiran
                                        </a>
                                    </template>
                                    <template x-if="!detailData || !detailData.pengajuan || !detailData.pengajuan.attachment_url">
                                        <p class="text-sm text-slate-400">Tidak ada lampiran</p>
                                    </template>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-200" x-data="{ 
                                maxLength: 80,
                                isExpanded: false,
                                get reason() {
                                    return detailData && detailData.pengajuan ? (detailData.pengajuan.reason || '') : '';
                                },
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
                            }" x-init="$watch('detailData', () => {
                                isExpanded = false;
                            })">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Alasan & Catatan</label>
                                <div class="bg-white rounded-lg p-3 border border-slate-200 w-full" 
                                     :class="!isExpanded && reason && reason.length > maxLength ? 'max-h-32 overflow-hidden' : ''">
                                    <p class="text-sm text-slate-700 break-words overflow-wrap-anywhere w-full" 
                                       :class="isExpanded ? 'whitespace-pre-wrap' : 'whitespace-normal'"
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
                        </div>

                        <!-- Data Pemohon -->
                        <div class="bg-slate-50 rounded-2xl p-6 mb-6 border border-slate-200">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Data Pemohon</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Nama</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pemohon ? detailData.pemohon.name : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Email</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pemohon ? detailData.pemohon.email : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Divisi</label>
                                    <p class="text-sm font-medium text-slate-900" x-text="detailData && detailData.pemohon ? detailData.pemohon.division : ''"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Status Terakhir</label>
                                    <span 
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold"
                                        :class="{
                                            'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': detailData && detailData.pengajuan && detailData.pengajuan.status_color === 'green',
                                            'bg-amber-50 text-amber-700 ring-1 ring-amber-200': detailData && detailData.pengajuan && detailData.pengajuan.status_color === 'orange',
                                            'bg-rose-50 text-rose-700 ring-1 ring-rose-200': detailData && detailData.pengajuan && detailData.pengajuan.status_color === 'red',
                                            'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200': detailData && detailData.pengajuan && detailData.pengajuan.status_color === 'indigo',
                                            'bg-slate-50 text-slate-700 ring-1 ring-slate-200': detailData && detailData.pengajuan && detailData.pengajuan.status_color === 'slate'
                                        }"
                                        x-text="detailData && detailData.pengajuan ? detailData.pengajuan.status_label : ''">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                            <h3 class="text-xl font-bold text-slate-900 mb-8">Timeline Status Pengajuan</h3>
                            <div class="relative pl-2 md:pl-0">
                                <!-- Vertical Line - More subtle -->
                                <div class="absolute left-3 md:left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-slate-200 via-slate-200 to-slate-200"></div>
                                
                                <!-- Timeline Items -->
                                <div class="space-y-5">
                                    <template x-for="(item, index) in (detailData && detailData.timeline ? detailData.timeline : [])" :key="index">
                                        <div 
                                            class="relative flex items-start gap-4 md:gap-5 group"
                                            x-data="{ 
                                                isVisible: false,
                                                init() {
                                                    setTimeout(() => {
                                                        this.isVisible = true;
                                                    }, index * 100);
                                                }
                                            }"
                                            x-show="isVisible"
                                            x-transition:enter="transition ease-out duration-500"
                                            x-transition:enter-start="opacity-0 translate-x-4"
                                            x-transition:enter-end="opacity-100 translate-x-0">
                                            
                                            <!-- Premium Dot with Glow -->
                                            <div class="relative z-10 flex-shrink-0 ml-1 md:ml-0">
                                                <div 
                                                    class="relative w-9 h-9 md:w-12 md:h-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                                                    :class="{
                                                        'bg-gradient-to-br from-slate-400 to-slate-500 ring-2 ring-slate-200 ring-offset-2 ring-offset-white shadow-[0_0_0_4px_rgba(148,163,184,0.1)]': item.color === 'slate' && item.status !== 'canceled',
                                                        'bg-gradient-to-br from-emerald-500 to-emerald-600 ring-2 ring-emerald-200 ring-offset-2 ring-offset-white shadow-[0_0_0_4px_rgba(16,185,129,0.15)]': item.color === 'green',
                                                        'bg-gradient-to-br from-rose-500 to-rose-600 ring-2 ring-rose-200 ring-offset-2 ring-offset-white shadow-[0_0_0_4px_rgba(244,63,94,0.15)]': item.color === 'red' || item.status === 'canceled',
                                                        'bg-gradient-to-br from-amber-400 to-amber-500 ring-2 ring-amber-200 ring-offset-2 ring-offset-white shadow-[0_0_0_4px_rgba(251,191,36,0.15)]': item.color === 'orange',
                                                        'bg-gradient-to-br from-indigo-500 to-indigo-600 ring-2 ring-indigo-200 ring-offset-2 ring-offset-white shadow-[0_0_0_4px_rgba(99,102,241,0.15)]': item.color === 'indigo'
                                                    }">
                                                    
                                                    <!-- Icon based on status -->
                                                    <template x-if="item.color === 'green'">
                                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </template>
                                                    <template x-if="item.status === 'canceled'">
                                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </template>
                                                    <template x-if="item.color === 'red' && item.status !== 'canceled'">
                                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </template>
                                                    <template x-if="(item.color === 'slate' || item.color === 'orange' || item.color === 'indigo') && item.status !== 'canceled'">
                                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </template>
                                                </div>
                                            </div>
                                            
                                            <!-- Premium Card Content -->
                                            <div class="flex-1 min-w-0 pt-1">
                                                <div 
                                                    class="bg-white rounded-xl p-5 md:p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 group-hover:border-slate-300"
                                                    :class="{
                                                        'bg-slate-50/50': item.color === 'slate' && item.status !== 'canceled',
                                                        'bg-emerald-50/30': item.color === 'green',
                                                        'bg-rose-50/30': item.color === 'red' || item.status === 'canceled',
                                                        'bg-amber-50/30': item.color === 'orange',
                                                        'bg-indigo-50/30': item.color === 'indigo'
                                                    }">
                                                    
                                                    <!-- Header with Icon -->
                                                    <div class="flex items-start gap-3 mb-3">
                                                        <div class="flex-shrink-0 mt-0.5">
                                                            <template x-if="item.color === 'green'">
                                                                <div class="p-1.5 rounded-lg bg-emerald-100">
                                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                            <template x-if="item.status === 'canceled'">
                                                                <div class="p-1.5 rounded-lg bg-rose-100">
                                                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                            <template x-if="item.color === 'red' && item.status !== 'canceled'">
                                                                <div class="p-1.5 rounded-lg bg-rose-100">
                                                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                            <template x-if="(item.color === 'slate' || item.color === 'orange' || item.color === 'indigo') && item.status !== 'canceled'">
                                                                <div class="p-1.5 rounded-lg bg-slate-100">
                                                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h4 
                                                                class="text-base md:text-lg font-bold mb-1.5"
                                                                :class="{
                                                                    'text-slate-700': item.color === 'slate',
                                                                    'text-emerald-700': item.color === 'green',
                                                                    'text-rose-700': item.color === 'red',
                                                                    'text-amber-700': item.color === 'orange',
                                                                    'text-indigo-700': item.color === 'indigo'
                                                                }"
                                                                x-text="item.title"></h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Description -->
                                                    <p class="text-sm md:text-base text-slate-600 mb-4 leading-relaxed" x-text="item.description"></p>
                                                    
                                                    <!-- Metadata Section -->
                                                    <div class="space-y-2 pt-3 border-t border-slate-200/60">
                                                        <!-- DateTime -->
                                                        <template x-if="item.datetime">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <p class="text-xs md:text-sm text-slate-500 font-medium" x-text="item.datetime"></p>
                                                            </div>
                                                        </template>
                                                        
                                                        <!-- Approver -->
                                                        <template x-if="item.approver">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                                <p class="text-xs md:text-sm text-slate-500">
                                                                    <span class="font-semibold">Oleh:</span> 
                                                                    <span class="text-slate-700 font-medium" x-text="item.approver"></span>
                                                                </p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <!-- Download PDF Button (only if approved) -->
                            <template x-if="detailData && detailData.pengajuan && detailData.pengajuan.status === 'approved'">
                                <a 
                                    :href="`/leave-requests/${detailData.pengajuan.id}/download-pdf`"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-all duration-200 hover:scale-105 shadow-sm hover:shadow-md"
                                    title="Download Surat Cuti (PDF)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Download Surat Cuti</span>
                                </a>
                            </template>
                        </div>
                        <button 
                            @click="closeModal()"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function leaveDetailModal() {
            return {
                mounted: false,
                showModal: false,
                loading: false,
                detailData: null,
                openDetailModal(id) {
                    console.log('openDetailModal called with id:', id);
                    this.showModal = true;
                    this.loading = true;
                    this.detailData = null;
                    
                    const url = '/leave-requests/' + id + '/detail';
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Failed to fetch detail: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data);
                        this.detailData = data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail pengajuan cuti: ' + error.message);
                        this.loading = false;
                        this.showModal = false;
                    });
                },
                closeModal() {
                    this.showModal = false;
                    this.detailData = null;
                },
                init() {
                    this.mounted = true;
                    this.$watch('showModal', value => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });
                    // Store function in window for easy access
                    const component = this;
                    window.openLeaveDetailModal = (id) => {
                        component.openDetailModal(id);
                    };
                }
            };
        }
    </script>
</x-app-layout>
