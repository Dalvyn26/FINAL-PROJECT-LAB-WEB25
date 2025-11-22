<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('HRD Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Leaves This Month -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10m-10 4h10m4-16v16m-4-16v16m-4-16v16m-4-16v16"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-slate-600">Total Leave Requests (This Month)</h3>
                            <p class="text-3xl font-bold text-slate-800">{{ $totalLeavesThisMonth }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Final Approvals -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg transition-all hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-400/20">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold opacity-90">Requests Need Final Approval</h3>
                                <p class="text-3xl font-bold">{{ $pendingFinalApprovals }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('hrd.leave-requests.index') }}" class="inline-block bg-white text-amber-600 text-sm font-bold py-2 px-4 rounded-lg transition-all hover:bg-amber-50">
                            Review Pending Requests
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Month Leave Overview -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 mb-8 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Absensi Cuti Bulan Ini</h3>
                
                @if($onLeaveThisMonth->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Division</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Leave Dates</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Days</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($onLeaveThisMonth as $request)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-700 font-medium">{{ strtoupper(substr($request->user->name, 0, 1)) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900">{{ $request->user->name }}</div>
                                                    <div class="text-sm text-slate-500">{{ $request->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $request->user->division ? $request->user->division->name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $request->total_days }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
                                                {{ $request->isAnnualLeave() ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($request->leave_type) . ' Leave' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada karyawan cuti bulan ini</h3>
                        <p class="mt-1 text-sm text-slate-500">Tidak ada karyawan yang sedang cuti pada periode ini.</p>
                    </div>
                @endif
            </div>

            <!-- Division Overview -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Overview Divisi</h3>
                
                @if($divisions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($divisions as $division)
                            <div class="border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all">
                                <div class="flex flex-col space-y-2">
                                    <h4 class="font-bold text-slate-800">{{ $division->name }}</h4>
                                    <p class="text-sm text-slate-600">{{ $division->description ?: 'No description' }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <div>
                                            <p class="text-xs text-slate-500">Leader:</p>
                                            @if($division->leader)
                                                <p class="text-sm font-medium text-slate-700">{{ $division->leader->name }}</p>
                                            @else
                                                <p class="text-sm text-slate-400 italic">No leader assigned</p>
                                            @endif
                                        </div>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800">
                                            {{ $division->users_count }} Staff
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-slate-500">No divisions created yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>