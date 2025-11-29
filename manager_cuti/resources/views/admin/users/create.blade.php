<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-slate-800 leading-tight">
                    Create User
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Add a new employee to your organization</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen animate-fade-in">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 animate-fade-up">
                <!-- Gradient Top Border -->
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-indigo-400 to-indigo-500"></div>
                
                <div class="p-4 sm:p-6 lg:p-8">

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-2 animate-fade-up">
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

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- User Information Section -->
                        <div class="mb-8">
                            <h4 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-200 flex items-center gap-2 animate-fade-up">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                User Information
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-6">
                                    <label for="username" class="block text-sm font-bold text-slate-700 mb-2">
                                        Username
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="username" id="username" value="{{ old('username') }}" required 
                                           placeholder="Enter username"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                    @error('username')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                                       Full Name
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                           placeholder="Enter full name"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                    @error('name')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                                        Email
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                           placeholder="Enter email address"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                    @error('email')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                                        Password
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <input type="password" name="password" id="password" required 
                                           placeholder="Enter password (min. 8 characters)"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                    @error('password')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">
                                        Confirm Password
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                                           placeholder="Confirm password"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- User Details Section -->
                        <div class="mb-8">
                            <h4 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-200 flex items-center gap-2 animate-fade-up" style="animation-delay: 100ms;">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                User Details
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-6">
                                    <label for="role" class="block text-sm font-bold text-slate-700 mb-2">
                                        Role
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="role" id="role" required 
                                                class="w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all {{ $hrdExists ? '' : '' }}">
                                            <option value="">Select Role</option>
                                            @if(!$hrdExists)
                                                <option value="hrd" {{ old('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                            @else
                                                <option value="hrd" disabled {{ old('role') == 'hrd' ? 'selected' : '' }}>HRD (Already Exists)</option>
                                            @endif
                                            <option value="division_leader" {{ old('role') == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @if($hrdExists)
                                        <p class="mt-2 text-xs text-amber-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            HRD role already exists. Only one HRD is allowed in the system.
                                        </p>
                                    @endif
                                    @error('role')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="leave_quota" class="block text-sm font-bold text-slate-700 mb-2">
                                        Kuota Cuti Awal
                                        <span class="text-rose-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="leave_quota" id="leave_quota" value="{{ old('leave_quota', 12) }}" required min="0" max="365"
                                               placeholder="Enter leave quota"
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-500">days</span>
                                    </div>
                                    @error('leave_quota')
                                        <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ route('admin.users.index') }}" class="group inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-6 rounded-full transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-6 rounded-full transition-all duration-200 hover:shadow-lg hover:scale-105">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>