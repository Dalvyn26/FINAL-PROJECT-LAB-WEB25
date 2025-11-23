<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Division Leader Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Incoming Requests Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-slate-600">Total Leave Requests</h3>
                            <p class="text-3xl font-bold text-slate-800">{{ $totalRequests }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending for Review Card -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md relative overflow-hidden">
                    <!-- Decorative triangle -->
                    <div class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                        Action Required
                    </div>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-100">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-slate-600">Pending for Review</h3>
                            <p class="text-3xl font-bold text-amber-600">{{ $pendingRequests }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('leader.leave-requests.index') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-all hover:-translate-y-0.5">
                            Review Requests
                        </a>
                    </div>
                </div>
            </div>

            <!-- Who is on Leave This Week -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 mb-8 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Sedang Cuti Minggu Ini</h3>
                
                @if($onLeaveThisWeek->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($onLeaveThisWeek as $request)
                            <div class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <x-avatar :user="$request->user" classes="w-10 h-10 mr-3" />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-800">{{ $request->user->name }}</p>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full
                                            {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($request->leave_type) . ' Leave' }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="mt-2 text-sm font-medium text-slate-800">Semua anggota hadir</h4>
                        <p class="mt-1 text-sm text-slate-500">Tidak ada anggota divisi yang sedang cuti minggu ini.</p>
                    </div>
                @endif
            </div>

            <!-- Division Members -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Daftar Anggota Divisi</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Quota</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <x-avatar :user="$member" classes="w-10 h-10 mr-3" />
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">{{ $member->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $member->leave_quota }} days
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs font-bold rounded-full 
                                            {{ $member->active_status ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $member->active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">
                                        No members in your division.
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