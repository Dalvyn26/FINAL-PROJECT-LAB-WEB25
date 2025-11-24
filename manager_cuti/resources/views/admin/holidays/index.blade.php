<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    Cuti-in Holiday Management
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage company holidays and national holidays</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen animate-fade-in" x-data="holidayModal()">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 animate-fade-up">
                <!-- Gradient Top Border -->
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-indigo-400 to-indigo-500"></div>
                
                <!-- Header Section with Action Buttons -->
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Hari Libur</h3>
                            <p class="text-xs text-slate-500 mt-1">Total {{ $holidays->total() }} holidays</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <!-- Sync Google Calendar Button -->
                            <form action="{{ route('admin.holidays.sync') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="group inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                        title="Import libur nasional otomatis">
                                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12m0 0l4-4m-4 4l-4 4"></path>
                                    </svg>
                                    Sync Google Calendar
                                </button>
                            </form>
                            <!-- Add Holiday Button -->
                            <button @click="openModal()" 
                                    class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-6 rounded-full transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 hover:scale-105">
                                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Holiday
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div class="px-6 pt-6">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 animate-fade-up">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-4 flex items-start gap-2 animate-fade-up">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Filter Toolbar -->
                <div class="px-6 pb-6" x-data="{ filtersOpen: false, isDesktop: window.innerWidth >= 1024 }" @resize.window="isDesktop = window.innerWidth >= 1024">
                    <!-- Mobile Filter Toggle -->
                    <div class="lg:hidden mb-4">
                        <button @click="filtersOpen = !filtersOpen" type="button" class="w-full flex items-center justify-between px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all">
                            <span class="text-sm font-semibold text-slate-700">Filters</span>
                            <svg class="w-5 h-5 text-slate-500 transition-transform duration-200" :class="{ 'rotate-180': filtersOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('admin.holidays.index') }}" 
                          class="bg-white/80 backdrop-blur-sm border border-slate-200/60 rounded-2xl p-6 shadow-sm transition-all duration-300 animate-fade-up hidden lg:block"
                          :class="{ 'hidden': !filtersOpen && !isDesktop, 'block': filtersOpen || isDesktop }"
                          x-show="filtersOpen || isDesktop"
                          x-transition:enter="transition ease-out duration-200"
                          x-transition:enter-start="opacity-0 transform -translate-y-2"
                          x-transition:enter-end="opacity-100 transform translate-y-0"
                          style="animation-delay: 50ms;">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label for="search" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search holiday name..." 
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                            
                            <!-- Filter Dropdown -->
                            <div>
                                <label for="filter" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Filter</label>
                                <div class="relative">
                                    <select name="filter" id="filter" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">All Holidays</option>
                                        <option value="national" {{ request('filter') == 'national' ? 'selected' : '' }}>National Holidays</option>
                                        <option value="manual" {{ request('filter') == 'manual' ? 'selected' : '' }}>Manual Holidays</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sort Dropdown -->
                            <div>
                                <label for="sort" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Sort By</label>
                                <div class="relative">
                                    <select name="sort" id="sort" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="holiday_date_asc" {{ request('sort') == 'holiday_date_asc' ? 'selected' : '' }}>Date (Ascending)</option>
                                        <option value="holiday_date_desc" {{ request('sort') == 'holiday_date_desc' ? 'selected' : '' }}>Date (Descending)</option>
                                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-end gap-3">
                                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-5 rounded-full transition-all duration-200 hover:shadow-lg hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('admin.holidays.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-full transition-all duration-200 hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200">Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200">Nama Libur</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200">Tipe</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($holidays as $index => $holiday)
                                <tr class="hover:bg-indigo-50/30 transition-all duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-bold text-slate-900">
                                                {{ $holiday->holiday_date->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                {{ $holiday->holiday_date->translatedFormat('l') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">{{ $holiday->title }}</div>
                                            @if($holiday->description)
                                                <div class="text-xs text-slate-500 mt-1">{{ Str::limit($holiday->description, 60) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($holiday->is_national_holiday)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 ring-1 ring-emerald-200 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                National Holiday
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 ring-1 ring-blue-200 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                Company Holiday
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button @click="openModal({{ $holiday->id }}, '{{ $holiday->title }}', '{{ $holiday->holiday_date->format('Y-m-d') }}', '{{ addslashes($holiday->description ?? '') }}')" 
                                                    class="group p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-110">
                                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin menghapus hari libur ini?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="group p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all duration-200 hover:scale-110">
                                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-semibold text-slate-900 mb-1">No holidays found</h3>
                                            <p class="text-sm text-slate-500">Try adjusting your filters to see more results.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden px-6 pb-6 space-y-4 pt-6">
                    @forelse($holidays as $holiday)
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-bold text-slate-900">{{ $holiday->holiday_date->format('d M Y') }}</div>
                                            <div class="text-xs text-slate-500">{{ $holiday->holiday_date->translatedFormat('l') }}</div>
                                        </div>
                                    </div>
                                    <h3 class="text-base font-semibold text-slate-900 mb-1">{{ $holiday->title }}</h3>
                                    @if($holiday->description)
                                        <p class="text-xs text-slate-500">{{ Str::limit($holiday->description, 80) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <button @click="openModal({{ $holiday->id }}, '{{ $holiday->title }}', '{{ $holiday->holiday_date->format('Y-m-d') }}', '{{ addslashes($holiday->description ?? '') }}')" 
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin menghapus hari libur ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-slate-100">
                                @if($holiday->is_national_holiday)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        National Holiday
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 ring-1 ring-blue-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Company Holiday
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900 mb-1">No holidays found</h3>
                                <p class="text-sm text-slate-500">Try adjusting your filters to see more results.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/30">
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Create/Edit (Shared Modal with Alpine.js) -->
        <div x-show="isOpen" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>
            
            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative z-50"
                     @click.away="closeModal()"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-slate-800" x-text="isEdit ? 'Edit Holiday' : 'Add New Holiday'"></h3>
                        <button @click="closeModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="isEdit ? '/admin/holidays/' + holidayId : '{{ route('admin.holidays.store') }}'" 
                          method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="modal_title" class="block text-sm font-medium text-slate-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="modal_title" 
                                   name="title" 
                                   x-model="formData.title"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g., Hari Raya Idul Fitri">
                        </div>

                        <!-- Date Field -->
                        <div class="mb-4">
                            <label for="modal_date" class="block text-sm font-medium text-slate-700 mb-1">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="modal_date" 
                                   name="holiday_date" 
                                   x-model="formData.holiday_date"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="modal_description" class="block text-sm font-medium text-slate-700 mb-1">
                                Description
                            </label>
                            <textarea id="modal_description" 
                                      name="description" 
                                      x-model="formData.description"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Optional description about this holiday"></textarea>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" 
                                    @click="closeModal()"
                                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                <span x-text="isEdit ? 'Update' : 'Create'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function holidayModal() {
            return {
                isOpen: false,
                isEdit: false,
                holidayId: null,
                formData: {
                    title: '',
                    holiday_date: '',
                    description: ''
                },

                openModal(holidayId = null, title = '', date = '', description = '') {
                    if (holidayId) {
                        // Edit mode
                        this.isEdit = true;
                        this.holidayId = holidayId;
                        this.formData = {
                            title: title,
                            holiday_date: date,
                            description: description || ''
                        };
                    } else {
                        // Create mode
                        this.isEdit = false;
                        this.holidayId = null;
                        this.formData = {
                            title: '',
                            holiday_date: '',
                            description: ''
                        };
                    }
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                    // Reset form after animation
                    setTimeout(() => {
                        this.isEdit = false;
                        this.holidayId = null;
                        this.formData = {
                            title: '',
                            holiday_date: '',
                            description: ''
                        };
                    }, 300);
                }
            }
        }
    </script>
</x-app-layout>
