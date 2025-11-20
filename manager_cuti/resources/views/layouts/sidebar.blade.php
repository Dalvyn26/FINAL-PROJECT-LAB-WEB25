<div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 z-30">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="flex items-center h-16 px-6 border-b border-slate-200">
            <div class="text-xl font-bold text-slate-800">
                Leave Management
            </div>
        </div>
        
        <!-- Menu -->
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1">
                <!-- General Group -->
                <div class="px-4 mb-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">General</h3>
                </div>
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <!-- Profile -->
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                
                @if(Auth::user()->role == 'admin')
                    <!-- Admin Group -->
                    <div class="px-4 mb-4 mt-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Admin</h3>
                    </div>
                    
                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        User Management
                    </a>
                    
                    <!-- Division Management -->
                    <a href="{{ route('admin.divisions.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.divisions.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Division Management
                    </a>
                @endif
                
                @if(Auth::user()->role == 'hrd')
                    <!-- HRD Group -->
                    <div class="px-4 mb-4 mt-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">HRD</h3>
                    </div>
                    
                    <!-- Final Approval -->
                    <a href="{{ route('hrd.leave-requests.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('hrd.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Final Approval
                    </a>
                    
                    <!-- Leave Summary -->
                    <a href="{{ route('hrd.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('hrd.dashboard') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Leave Summary
                    </a>
                @endif
                
                @if(Auth::user()->role == 'division_leader')
                    <!-- Leader Group -->
                    <div class="px-4 mb-4 mt-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Division Leader</h3>
                    </div>
                    
                    <!-- Team Approval -->
                    <a href="{{ route('leader.leave-requests.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('leader.leave-requests.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Team Approval
                    </a>
                @endif
                
                @if(Auth::user()->role == 'user' || Auth::user()->role == 'division_leader')
                    <!-- Employee Group -->
                    <div class="px-4 mb-4 mt-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Employee</h3>
                    </div>
                    
                    <!-- Request Leave -->
                    <a href="{{ route('leave-requests.create') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('leave-requests.create') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Request Leave
                    </a>
                    
                    <!-- My Leave History -->
                    <a href="{{ route('leave-requests.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('leave-requests.index') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        My Leave History
                    </a>
                @endif
            </nav>
        </div>
        
        <!-- User Info & Logout -->
        <div class="border-t border-slate-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 rounded-full text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 text-capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>