<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('My Leave History') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Ringkas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Cuti Tahun Ini -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 relative overflow-hidden transition-all">
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                    <div class="absolute top-4 right-4 opacity-10">
                        <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-600">Total Pengajuan Cuti Tahun Ini</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalLeavesThisYear }}</p>
                    </div>
                </div>

                <!-- Sisa Kuota -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 relative overflow-hidden transition-all">
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
                    <div class="absolute top-4 right-4 opacity-10">
                        <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-600">Sisa Kuota</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1">{{ Auth::user()->leave_quota }}</p>
                    </div>
                </div>

                <!-- Total Cuti Sakit -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 relative overflow-hidden transition-all">
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-500"></div>
                    <div class="absolute top-4 right-4 opacity-10">
                        <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-600">Total Pengajuan Cuti Sakit</p>
                        <p class="text-3xl font-bold text-slate-800 mt-1">{{ $sickLeavesThisYear }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline / List Riwayat -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Riwayat Pengajuan Cuti</h3>

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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Cuti</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Alasan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leaveRequests as $request)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs font-bold rounded-full 
                                                {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($request->leave_type) . ' Leave' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $request->total_days }} days
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs">
                                            {{ Str::limit($request->reason, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
                                                    @if($request->isPending()) bg-amber-100 text-amber-800
                                                    @elseif($request->isApprovedByLeader()) bg-blue-100 text-blue-800
                                                    @elseif($request->isApproved()) bg-emerald-100 text-emerald-800
                                                    @elseif($request->isRejected()) bg-rose-100 text-rose-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                                @if($request->isRejected() && $request->rejection_note)
                                                    <span class="text-xs text-rose-600 mt-1" title="{{ $request->rejection_note }}">
                                                        {{ Str::limit($request->rejection_note, 30) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($request->user_id === auth()->id() && $request->isPending())
                                                <form action="{{ route('leave-requests.cancel', $request) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="text-slate-500 hover:text-red-600 font-medium text-sm transition" 
                                                            onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $leaveRequests->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No leave history</h3>
                        <p class="mt-1 text-sm text-slate-500">You haven't made any leave requests yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>