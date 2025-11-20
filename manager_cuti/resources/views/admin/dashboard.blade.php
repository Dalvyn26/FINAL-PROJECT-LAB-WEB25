<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Total Users</h3>
                            <p class="text-3xl font-bold text-slate-800">{{ \App\Models\User::count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Divisions Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-100">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-slate-600">Total Divisions</h3>
                            <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Division::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Recent Leave Requests</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(\App\Models\LeaveRequest::with('user')->latest()->take(5)->get() as $request)
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">
                                        No leave requests found.
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