<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Leave Balance Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Remaining Leave Quota</h3>
                            <p class="text-4xl font-bold text-slate-800 mt-1">{{ Auth::user()->leave_quota }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-100">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Quick Actions</h3>
                            <a href="{{ route('leave-requests.create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-all hover:-translate-y-0.5">
                                Request Leave
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave History Card -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Leave History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(Auth::user()->leaveRequests()->latest()->take(5)->get() as $request)
                                <tr class="hover:bg-gray-50">
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
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ Str::limit($request->reason, 50) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                        No leave requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(Auth::user()->leaveRequests()->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('leave-requests.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-all hover:-translate-y-0.5">
                            View All Leave Requests
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>