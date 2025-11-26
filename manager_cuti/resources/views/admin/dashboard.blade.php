<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-slate-800 leading-tight">
                    Cuti-in Admin Dashboard
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Overview & Analytics</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Total Employees Card -->
                <div class="group bg-white border border-slate-200/60 shadow-sm rounded-2xl p-4 sm:p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 animate-fade-up" style="animation-delay: 0ms;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div class="flex-shrink-0 h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                    <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-500 mb-1">Total Employees</h4>
                            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $totalEmployees }}</p>
                            <p class="text-xs text-slate-500">
                                <span class="font-medium text-emerald-600">{{ $activeEmployees }}</span> Active / 
                                <span class="font-medium text-slate-400">{{ $inactiveEmployees }}</span> Inactive
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Divisions Card -->
                <div class="group bg-white border border-slate-200/60 shadow-sm rounded-2xl p-4 sm:p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 animate-fade-up" style="animation-delay: 50ms;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div class="flex-shrink-0 h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                    <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-semibold text-slate-500 mb-1">Total Divisions</h4>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $totalDivisions }}</p>
                        </div>
                    </div>
                </div>

                <!-- Leaves This Month Card -->
                <div class="group bg-white border border-slate-200/60 shadow-sm rounded-2xl p-4 sm:p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 animate-fade-up" style="animation-delay: 100ms;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div class="flex-shrink-0 h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                                    <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-semibold text-slate-500 mb-1">Cuti Bulan Ini</h4>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $leavesThisMonth }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Approval Card -->
                <div class="group bg-white border border-slate-200/60 shadow-sm rounded-2xl p-4 sm:p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 animate-fade-up" style="animation-delay: 150ms;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div class="flex-shrink-0 h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
                                    <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-semibold text-slate-500 mb-1">Pending Approval</h4>
                            <p class="text-2xl sm:text-3xl font-bold text-rose-600">{{ $pendingLeaves }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ineligible Employees Section -->
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 animate-fade-up" style="animation-delay: 200ms;">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Karyawan Belum Eligible Cuti Tahunan (&lt; 1 Tahun)</h3>
                    <p class="text-xs text-slate-500 mt-1">Daftar karyawan yang belum mencapai masa kerja 1 tahun</p>
                </div>
                
                @if($newEmployees->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Division</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Join Date</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Work Period</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($newEmployees as $index => $employee)
                                    <tr class="hover:bg-indigo-50/30 transition-all duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($employee->avatar)
                                                        <img src="{{ Storage::url($employee->avatar) }}?v={{ time() }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $employee->name }}">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name ?? 'User') }}&background=4F46E5&color=fff" class="w-11 h-11 rounded-full ring-2 ring-white shadow-sm" alt="{{ $employee->name }}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-slate-900">{{ $employee->name }}</div>
                                                    <div class="text-xs text-slate-500 mt-0.5">{{ $employee->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="text-sm font-medium text-slate-700">{{ $employee->division ? $employee->division->name : 'None' }}</span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($employee->join_date)->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}</span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $employee->active_status ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-1 ring-rose-200' }}">
                                                {{ $employee->active_status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden px-4 sm:px-6 py-4 sm:py-6 space-y-3">
                        @foreach($newEmployees as $employee)
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex-shrink-0">
                                        @if($employee->avatar)
                                            <img src="{{ Storage::url($employee->avatar) }}?v={{ time() }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $employee->name }}">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name ?? 'User') }}&background=4F46E5&color=fff" class="w-12 h-12 rounded-full ring-2 ring-white shadow-sm" alt="{{ $employee->name }}">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $employee->name }}</h3>
                                        <p class="text-xs text-slate-500 truncate">{{ $employee->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 pt-3 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500">Division:</span>
                                        <span class="text-xs font-medium text-slate-700">{{ $employee->division ? $employee->division->name : 'None' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500">Join Date:</span>
                                        <span class="text-xs text-slate-700">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500">Work Period:</span>
                                        <span class="text-xs text-slate-700">{{ \Carbon\Carbon::parse($employee->join_date)->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500">Status:</span>
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full {{ $employee->active_status ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-1 ring-rose-200' }}">
                                            {{ $employee->active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-emerald-100 mb-4">
                            <svg class="h-6 w-6 sm:h-8 sm:w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-slate-900">Tidak ada karyawan</h3>
                        <p class="mt-1 text-xs sm:text-sm text-slate-500">Semua karyawan eligible untuk cuti tahunan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>