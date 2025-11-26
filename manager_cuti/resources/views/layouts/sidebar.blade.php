<!-- Mobile sidebar backdrop and menu -->
<div 
    x-show="sidebarOpen"
    @keydown.escape.window="sidebarOpen = false"
    x-cloak
    class="fixed inset-0 z-40 lg:hidden"
    x-transition:enter="duration-300 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="duration-200 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    
    <!-- Backdrop -->
    <div 
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50"
        x-transition:enter="duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>
    
    <!-- Mobile sidebar -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="duration-300 ease-out"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex-1 flex max-w-xs w-full bg-white border-r border-slate-100 shadow-xl z-50">
        
        <div class="flex-1 h-full flex flex-col bg-white shadow-sm">
            <!-- Logo/Brand -->
            <div class="flex items-center h-16 px-6 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <!-- Logo Icon -->
                    <div class="flex items-center justify-center w-9 h-9">
                        <img src="{{ asset('logo/LogoCutiin.png') }}" alt="Cuti-in Logo" class="w-9 h-9">
                    </div>
                    <div class="text-lg font-bold text-slate-800 tracking-tight">
                        Cuti-in
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="space-y-1 px-2">
                    <!-- General Group -->
                    <div class="px-4 mb-3 mt-2">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">General</h3>
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       @click="sidebarOpen = false"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('dashboard') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('profile.edit') }}"
                       @click="sidebarOpen = false"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('profile.edit') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>

                    @if(Auth::user()->role == 'admin')
                        <!-- Admin Group -->
                        <div class="px-4 mb-3 mt-6">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin</h3>
                        </div>

                        <!-- User Management -->
                        <a href="{{ route('admin.users.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.users.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            User Management
                        </a>

                        <!-- Division Management -->
                        <a href="{{ route('admin.divisions.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.divisions.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.divisions.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Division Management
                        </a>

                        <!-- Holiday Management -->
                        <a href="{{ route('admin.holidays.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.holidays.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.holidays.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Hari Libur
                        </a>
                    @endif

                    @if(Auth::user()->role == 'hrd')
                        <!-- HRD Group -->
                        <div class="px-4 mb-3 mt-6">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">HRD</h3>
                        </div>

                        <!-- Final Approval -->
                        <a href="{{ route('hrd.leave-requests.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('hrd.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('hrd.leave-requests.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Final Approval
                        </a>

                        <!-- Leave Summary -->
                        <a href="{{ route('hrd.dashboard') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('hrd.dashboard') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('hrd.dashboard') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Leave Summary
                        </a>
                    @endif

                    @if(Auth::user()->role == 'division_leader')
                        <!-- Leader Group -->
                        <div class="px-4 mb-3 mt-6">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Division Leader</h3>
                        </div>

                        <!-- Team Approval -->
                        <a href="{{ route('leader.leave-requests.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leader.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leader.leave-requests.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Team Approval
                        </a>
                    @endif

                    @if(Auth::user()->role == 'user' || Auth::user()->role == 'division_leader')
                        <!-- Employee Group -->
                        <div class="px-4 mb-3 mt-6">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Employee</h3>
                        </div>

                        <!-- Request Leave -->
                        <a href="{{ route('leave-requests.create') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.create') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leave-requests.create') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Request Leave
                        </a>

                        <!-- My Leave History -->
                        <a href="{{ route('leave-requests.index') }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.index') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leave-requests.index') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            My Leave History
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Info & Logout -->
            <div class="border-t border-slate-100 p-4 bg-slate-50/50">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}?v={{ time() }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ Auth::user()->name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=4F46E5&color=fff" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm" alt="{{ Auth::user()->name }}">
                        @endif
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 text-capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Desktop sidebar -->
<div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:border-r lg:border-slate-100">
    <div class="flex flex-col h-full bg-white shadow-sm">
        <!-- Logo/Brand -->
        <div class="flex items-center h-16 px-6 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <!-- Logo Icon -->
                <div class="flex items-center justify-center w-9 h-9">
                    <img src="{{ asset('logo/LogoCutiin.png') }}" alt="Cuti-in Logo" class="w-9 h-9">
                </div>
                <div class="text-lg font-bold text-slate-800 tracking-tight">
                    Cuti-in
                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1 px-2">
                <!-- General Group -->
                <div class="px-4 mb-3 mt-2">
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">General</h3>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('dashboard') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}"
                   class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('profile.edit') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>

                @if(Auth::user()->role == 'admin')
                    <!-- Admin Group -->
                    <div class="px-4 mb-3 mt-6">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin</h3>
                    </div>

                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.users.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        User Management
                    </a>

                    <!-- Division Management -->
                    <a href="{{ route('admin.divisions.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.divisions.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.divisions.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Division Management
                    </a>

                    <!-- Holiday Management -->
                    <a href="{{ route('admin.holidays.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.holidays.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('admin.holidays.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Hari Libur
                    </a>
                @endif

                @if(Auth::user()->role == 'hrd')
                    <!-- HRD Group -->
                    <div class="px-4 mb-3 mt-6">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">HRD</h3>
                    </div>

                    <!-- Final Approval -->
                    <a href="{{ route('hrd.leave-requests.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('hrd.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('hrd.leave-requests.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Final Approval
                    </a>

                    <!-- Leave Summary -->
                    <a href="{{ route('hrd.dashboard') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('hrd.dashboard') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('hrd.dashboard') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Leave Summary
                    </a>
                @endif

                @if(Auth::user()->role == 'division_leader')
                    <!-- Leader Group -->
                    <div class="px-4 mb-3 mt-6">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Division Leader</h3>
                    </div>

                    <!-- Team Approval -->
                    <a href="{{ route('leader.leave-requests.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leader.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leader.leave-requests.*') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Team Approval
                    </a>
                @endif

                @if(Auth::user()->role == 'user' || Auth::user()->role == 'division_leader')
                    <!-- Employee Group -->
                    <div class="px-4 mb-3 mt-6">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Employee</h3>
                    </div>

                    <!-- Request Leave -->
                    <a href="{{ route('leave-requests.create') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.create') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leave-requests.create') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Request Leave
                    </a>

                    <!-- My Leave History -->
                    <a href="{{ route('leave-requests.index') }}"
                       class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.index') ? 'bg-indigo-50 text-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 transition-transform duration-200 {{ request()->routeIs('leave-requests.index') ? 'scale-110' : 'group-hover:scale-110' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        My Leave History
                    </a>
                @endif
            </nav>
        </div>

        <!-- User Info & Logout -->
        <div class="border-t border-slate-100 p-4 bg-slate-50/50">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}?v={{ time() }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ Auth::user()->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=4F46E5&color=fff" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm" alt="{{ Auth::user()->name }}">
                    @endif
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 text-capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>