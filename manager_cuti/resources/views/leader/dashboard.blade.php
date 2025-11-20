<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Division Leader Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pending Leave Requests for Division Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Pending Requests from Division</h3>
                            <?php
                            $user = auth()->user();
                            $pendingCount = \App\Models\LeaveRequest::whereHas('user', function ($query) use ($user) {
                                $query->where('division_id', $user->divisionLeader->id);
                            })->where('status', 'pending')->count();
                            ?>
                            <p class="text-3xl font-bold text-slate-800">{{ $pendingCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Division Members Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-100">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Division Members</h3>
                            <?php
                            $user = auth()->user();
                            $memberCount = \App\Models\User::where('division_id', $user->divisionLeader->id)->count();
                            ?>
                            <p class="text-3xl font-bold text-slate-800">{{ $memberCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Leave Requests Table -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Pending Leave Requests from Division</h3>
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
                            <?php
                            $user = auth()->user();
                            $pendingRequests = \App\Models\LeaveRequest::whereHas('user', function ($query) use ($user) {
                                $query->where('division_id', $user->divisionLeader->id);
                            })->where('status', 'pending')->with('user')->get();
                            ?>
                            @forelse($pendingRequests as $request)
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
                                        <a href="{{ route('leader.leave-requests.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-all hover:-translate-y-0.5">Process</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                        No pending leave requests from your division.
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