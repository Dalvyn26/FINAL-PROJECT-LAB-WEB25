<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    Cuti-in — Division Management
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Organize teams and manage divisions</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen animate-fade-in">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 animate-fade-up">
                <!-- Header Section -->
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Divisions</h3>
                            <p class="text-xs text-slate-500 mt-1">Total {{ $divisions->total() }} divisions</p>
                        </div>
                        <a href="{{ route('admin.divisions.create') }}" class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-5 rounded-full transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Division
                        </a>
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

                    <form method="GET" action="{{ route('admin.divisions.index') }}" 
                          class="bg-white/80 backdrop-blur-sm border border-slate-200/60 rounded-2xl p-6 shadow-sm transition-all duration-300 animate-fade-up hidden lg:block"
                          :class="{ 'hidden': !filtersOpen && !isDesktop, 'block': filtersOpen || isDesktop }"
                          x-show="filtersOpen || isDesktop"
                          x-transition:enter="transition ease-out duration-200"
                          x-transition:enter-start="opacity-0 transform -translate-y-2"
                          x-transition:enter-end="opacity-100 transform translate-y-0"
                          style="animation-delay: 50ms;">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2 lg:col-span-1">
                                <label for="search" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Division or leader..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                            
                            <!-- Sort By -->
                            <div>
                                <label for="sort" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Sort By</label>
                                <div class="relative">
                                    <select name="sort" id="sort" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="created_newest" {{ request('sort') == 'created_newest' ? 'selected' : '' }}>Created Newest</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                        <option value="members_most" {{ request('sort') == 'members_most' ? 'selected' : '' }}>Members Most</option>
                                        <option value="members_least" {{ request('sort') == 'members_least' ? 'selected' : '' }}>Members Least</option>
                                        <option value="created_oldest" {{ request('sort') == 'created_oldest' ? 'selected' : '' }}>Created Oldest</option>
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
                                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-lg hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('admin.divisions.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-md">
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
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Leader</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Members</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($divisions as $index => $division)
                                <tr class="hover:bg-indigo-50/30 transition-all duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                    <td class="px-6 py-5">
                                        <div>
                                            <div class="text-sm font-bold text-slate-900">{{ $division->name }}</div>
                                            @if($division->description)
                                                <div class="text-xs text-slate-500 mt-1">{{ Str::limit($division->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($division->leader)
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($division->leader->avatar)
                                                        <img src="{{ Storage::url($division->leader->avatar) }}?v={{ time() }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $division->leader->name }}">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($division->leader->name ?? 'User') }}&background=4F46E5&color=fff" class="w-11 h-11 rounded-full ring-2 ring-white shadow-sm" alt="{{ $division->leader->name }}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">{{ $division->leader->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $division->leader->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-400 italic">No Leader</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-violet-100 text-blue-700 ring-1 ring-blue-200 shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $division->users_count }} member{{ $division->users_count != 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.divisions.show', $division) }}" class="group p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200 hover:scale-110">
                                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.divisions.edit', $division) }}" class="group p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-110">
                                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin? Semua anggota di divisi ini akan dikeluarkan (menjadi Non-Divisi).">
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-semibold text-slate-900 mb-1">No divisions found</h3>
                                            <p class="text-sm text-slate-500">Try adjusting your filters to see more results.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden px-6 pb-6 space-y-4">
                    @forelse($divisions as $division)
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 animate-fade-up">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-base font-bold text-slate-900 mb-1">{{ $division->name }}</h3>
                                    @if($division->description)
                                        <p class="text-xs text-slate-500">{{ Str::limit($division->description, 60) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <a href="{{ route('admin.divisions.show', $division) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.divisions.edit', $division) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin? Semua anggota di divisi ini akan dikeluarkan (menjadi Non-Divisi).">
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
                            
                            <div class="space-y-3 pt-4 border-t border-slate-100">
                                <!-- Leader -->
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-semibold text-slate-500 w-16">Leader:</span>
                                    @if($division->leader)
                                        <div class="flex items-center gap-2 flex-1">
                                            @if($division->leader->avatar)
                                                <img src="{{ Storage::url($division->leader->avatar) }}?v={{ time() }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $division->leader->name }}">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($division->leader->name ?? 'User') }}&background=4F46E5&color=fff" class="w-8 h-8 rounded-full ring-2 ring-white shadow-sm" alt="{{ $division->leader->name }}">
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-semibold text-slate-900 truncate">{{ $division->leader->name }}</div>
                                                <div class="text-xs text-slate-500 truncate">{{ $division->leader->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">No Leader</span>
                                    @endif
                                </div>
                                
                                <!-- Members -->
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-semibold text-slate-500 w-16">Members:</span>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-violet-100 text-blue-700 ring-1 ring-blue-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        {{ $division->users_count }} member{{ $division->users_count != 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900 mb-1">No divisions found</h3>
                                <p class="text-sm text-slate-500">Try adjusting your filters to see more results.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/30">
                    {{ $divisions->links() }}
                </div>
            </div>

            <!-- Mobile Floating Add Button -->
            <a href="{{ route('admin.divisions.create') }}" class="fixed bottom-6 right-6 z-50 lg:hidden group inline-flex items-center justify-center w-14 h-14 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>
    </div>
</x-app-layout>