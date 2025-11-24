<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    Cuti-in User Management
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage all users, roles, and statuses</p>
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
                            <h3 class="text-lg font-bold text-slate-800">Users</h3>
                            <p class="text-xs text-slate-500 mt-1">Total {{ $users->total() }} users found</p>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add User
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

                    <form method="GET" action="{{ route('admin.users.index') }}" 
                          class="bg-white/80 backdrop-blur-sm border border-slate-200/60 rounded-2xl p-6 shadow-sm transition-all duration-300 animate-fade-up hidden lg:block"
                          :class="{ 'hidden': !filtersOpen && !isDesktop, 'block': filtersOpen || isDesktop }"
                          x-show="filtersOpen || isDesktop"
                          x-transition:enter="transition ease-out duration-200"
                          x-transition:enter-start="opacity-0 transform -translate-y-2"
                          x-transition:enter-end="opacity-100 transform translate-y-0"
                          style="animation-delay: 50ms;">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
                            <!-- Search Input -->
                            <div class="md:col-span-3 lg:col-span-1">
                                <label for="search" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name/Email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                </div>
                            </div>

                            <!-- Role Filter -->
                            <div>
                                <label for="role" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Role</label>
                                <div class="relative">
                                    <select name="role" id="role" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                        <option value="division_leader" {{ request('role') == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Division Filter -->
                            <div>
                                <label for="division_id" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Division</label>
                                <div class="relative">
                                    <select name="division_id" id="division_id" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">All Divisions</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Tenure Filter -->
                            <div>
                                <label for="tenure" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Tenure</label>
                                <div class="relative">
                                    <select name="tenure" id="tenure" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">All Tenures</option>
                                        <option value="<1" {{ request('tenure') == '<1' ? 'selected' : '' }}>&lt; 1 Year</option>
                                        <option value="1-3" {{ request('tenure') == '1-3' ? 'selected' : '' }}>1-3 Years</option>
                                        <option value=">3" {{ request('tenure') == '>3' ? 'selected' : '' }}>&gt; 3 Years</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Status</label>
                                <div class="relative">
                                    <select name="status" id="status" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Sort By -->
                            <div>
                                <label for="sort" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Sort By</label>
                                <div class="relative">
                                    <select name="sort" id="sort" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="name" {{ request('sort') == 'name' && request('direction') != 'desc' ? 'selected' : '' }}>Name (A-Z)</option>
                                        <option value="name_desc" {{ (request('sort') == 'name' && request('direction') == 'desc') || request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                        <option value="division" {{ request('sort') == 'division' && request('direction') != 'desc' ? 'selected' : '' }}>Division (A-Z)</option>
                                        <option value="division_desc" {{ (request('sort') == 'division' && request('direction') == 'desc') || request('sort') == 'division_desc' ? 'selected' : '' }}>Division (Z-A)</option>
                                        <option value="join_date" {{ request('sort') == 'join_date' && request('direction') != 'desc' ? 'selected' : '' }}>Join Date (Oldest)</option>
                                        <option value="join_date_desc" {{ (request('sort') == 'join_date' && request('direction') == 'desc') || request('sort') == 'join_date_desc' ? 'selected' : '' }}>Join Date (Latest)</option>
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
                                <a href="{{ route('admin.users.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 hover:text-indigo-600 transition-colors group">
                                        Name
                                        @if(request('sort') == 'name')
                                            <svg class="w-4 h-4 transition-transform duration-200 {{ request('direction') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'division', 'direction' => (request('sort') == 'division' && request('direction') == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 hover:text-indigo-600 transition-colors group">
                                        Division
                                        @if(request('sort') == 'division')
                                            <svg class="w-4 h-4 transition-transform duration-200 {{ request('direction') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'join_date', 'direction' => (request('sort') == 'join_date' && request('direction') == 'desc') ? 'asc' : 'desc']) }}" class="flex items-center gap-1.5 hover:text-indigo-600 transition-colors group">
                                        Join Date
                                        @if(request('sort') == 'join_date')
                                            <svg class="w-4 h-4 transition-transform duration-200 {{ request('direction') == 'desc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-50 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-indigo-50/30 transition-all duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if($user->avatar)
                                                    <img src="{{ Storage::url($user->avatar) }}?v={{ time() }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $user->name }}">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=4F46E5&color=fff" class="w-11 h-11 rounded-full ring-2 ring-white shadow-sm" alt="{{ $user->name }}">
                                                @endif
                                            </div>
                                            <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm text-slate-600">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                            @switch($user->role)
                                                @case('admin')
                                                    bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700 ring-1 ring-purple-200
                                                    @break
                                                @case('hrd')
                                                    bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 ring-1 ring-blue-200
                                                    @break
                                                @case('division_leader')
                                                    bg-gradient-to-r from-amber-100 to-amber-50 text-amber-700 ring-1 ring-amber-200
                                                    @break
                                                @case('user')
                                                    bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                                    @break
                                                @default
                                                    bg-gradient-to-r from-slate-100 to-slate-50 text-slate-700 ring-1 ring-slate-200
                                            @endswitch">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm font-medium text-slate-700">{{ $user->division ? $user->division->name : 'None' }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm text-slate-600">{{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $user->active_status ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200 shadow-sm shadow-emerald-200/50' : 'bg-rose-100 text-rose-700 ring-1 ring-rose-200' }}">
                                            {{ $user->active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="group p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200 hover:scale-110" title="View User">
                                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="group p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 hover:scale-110" title="Edit User">
                                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="group p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all duration-200 hover:scale-110" title="Delete User">
                                                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-semibold text-slate-900 mb-1">No users found</h3>
                                            <p class="text-sm text-slate-500">Try adjusting your filters to see more results.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            </div>

            <!-- Mobile Floating Add Button -->
            <a href="{{ route('admin.users.create') }}" class="fixed bottom-6 right-6 z-50 lg:hidden group inline-flex items-center justify-center w-14 h-14 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>
    </div>
</x-app-layout>