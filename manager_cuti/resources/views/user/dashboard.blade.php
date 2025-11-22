<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Sisa Kuota Card -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl p-6 text-white shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-indigo-400/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold opacity-80">Remaining Annual Leave</h3>
                            <p class="text-4xl font-bold mt-1">{{ $remainingQuota }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik Pengajuan Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-slate-600">Leave Statistics</h3>
                            <div class="mt-2 space-y-1">
                                <p class="text-lg font-semibold text-slate-800">{{ $totalLeaves }} total requests</p>
                                <p class="text-lg text-slate-600">{{ $totalSickLeave }} sick leave</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Divisi Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-emerald-100">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-slate-600">Division Information</h3>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm font-medium text-slate-800">
                                    <span class="font-semibold">Division:</span> 
                                    @if($user->division)
                                        {{ $user->division->name }}
                                    @else
                                        <span class="italic text-slate-500">Belum ada divisi</span>
                                    @endif
                                </p>
                                <p class="text-sm text-slate-800">
                                    <span class="font-semibold">Leader:</span> 
                                    @if($user->division && $user->division->leader)
                                        {{ $user->division->leader->name }}
                                    @else
                                        <span class="italic text-slate-500">Tidak ada ketua</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leave Requests -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Recent Leave Requests</h3>
                
                @if($user->leaveRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dates</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Days</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Submitted</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->leaveRequests()->latest()->take(5)->get() as $request)
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full
                                                @if($request->isPending()) bg-yellow-100 text-yellow-800
                                                @elseif($request->isApprovedByLeader()) bg-blue-100 text-blue-800
                                                @elseif($request->isApproved()) bg-emerald-100 text-emerald-800
                                                @elseif($request->isRejected()) bg-rose-100 text-rose-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($user->leaveRequests()->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('leave-requests.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                View All Requests
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No leave requests</h3>
                        <p class="mt-1 text-sm text-slate-500">You haven't made any leave requests yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent text-sm font-medium rounded-md text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Request Leave
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>