<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('HRD Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Need Approval Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-100">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Requests Need Approval</h3>
                            <p class="text-3xl font-bold text-slate-800">{{ \App\Models\LeaveRequest::where('status', 'approved_by_leader')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employees on Leave Today Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-rose-100">
                            <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Employees on Leave Today</h3>
                            <?php
                            $today = \Carbon\Carbon::today();
                            $onLeaveCount = \App\Models\LeaveRequest::where('status', 'approved')
                                ->whereDate('start_date', '<=', $today)
                                ->whereDate('end_date', '>=', $today)
                                ->count();
                            ?>
                            <p class="text-3xl font-bold text-slate-800">{{ $onLeaveCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending for Approval Table -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all mb-8">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Pending Requests for Approval</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(\App\Models\LeaveRequest::where('status', 'approved_by_leader')->with('user')->get() as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $request->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs font-bold rounded-full
                                            {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($request->leave_type) }}
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
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('hrd.leave-requests.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-all hover:-translate-y-0.5">Process</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                        No requests pending for final approval.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Employees Currently on Leave -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Employees Currently on Leave</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Division</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $currentLeaveRequests = \App\Models\LeaveRequest::where('status', 'approved')
                                ->whereDate('start_date', '<=', $today)
                                ->whereDate('end_date', '>=', $today)
                                ->with('user', 'user.division')
                                ->get();
                            ?>
                            @forelse($currentLeaveRequests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $request->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $request->user->division ? $request->user->division->name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $request->total_days }} days
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs font-bold rounded-full
                                            {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($request->leave_type) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                        No employees currently on leave.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>