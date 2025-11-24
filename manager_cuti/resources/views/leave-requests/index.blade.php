<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('My Leave History') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">View and manage your leave request history</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC]" x-data="{ mounted: false }" x-init="mounted = true">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <!-- Total Cuti Tahun Ini -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 0ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-4 rounded-2xl bg-blue-50 shadow-sm">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#6B7280] mb-1">Total Pengajuan Cuti</h3>
                                <p class="text-4xl font-bold text-slate-900 tracking-tight">{{ $totalLeavesThisYear }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This year</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisa Kuota -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 100ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-4 rounded-2xl bg-emerald-50 shadow-sm">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#6B7280] mb-1">Sisa Kuota</h3>
                                <p class="text-4xl font-bold text-slate-900 tracking-tight">{{ Auth::user()->leave_quota }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">Remaining days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Cuti Sakit -->
                <div class="group bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)] hover:-translate-y-1 relative overflow-hidden"
                     style="animation-delay: 200ms;">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-4 rounded-2xl bg-amber-50 shadow-sm">
                                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#6B7280] mb-1">Total Cuti Sakit</h3>
                                <p class="text-4xl font-bold text-slate-900 tracking-tight">{{ $sickLeavesThisYear }}</p>
                                <p class="text-xs text-[#6B7280] mt-1">This year</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline / List Riwayat -->
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300"
                 x-show="mounted"
                 x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-1">Riwayat Pengajuan Cuti</h3>
                        <p class="text-sm text-[#6B7280]">Complete history of your leave requests</p>
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
                    <div class="overflow-x-auto -mx-2">
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
                                            <td class="px-6 py-5 text-sm text-slate-600 max-w-xs">
                                                {{ Str::limit($request->reason, 50) }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex flex-col gap-1">
                                                    <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full shadow-sm
                                                        @if($request->isPending()) bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                                        @elseif($request->isApprovedByLeader()) bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200
                                                        @elseif($request->isApproved()) bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                                        @elseif($request->isRejected()) bg-rose-50 text-rose-700 ring-1 ring-rose-200
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                    </span>
                                                    @if($request->isRejected() && $request->rejection_note)
                                                        <span class="text-xs text-rose-600" title="{{ $request->rejection_note }}">
                                                            {{ Str::limit($request->rejection_note, 30) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-sm">
                                                <div class="flex items-center gap-2">
                                                    @if($request->user_id === auth()->id() && $request->isPending())
                                                        <form action="{{ route('leave-requests.cancel', $request) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="text-rose-600 hover:text-rose-700 font-medium text-sm transition-colors hover:underline" 
                                                                    onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($request->status === 'approved')
                                                        <a href="{{ route('leave-requests.download-pdf', $request) }}" 
                                                           class="inline-flex items-center justify-center w-9 h-9 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all duration-200 hover:scale-110 group"
                                                           title="Download Surat Cuti (PDF)">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
        </div>
    </div>
</x-app-layout>
