<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 leading-tight">
                        View User: {{ $user->name }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">User profile and information details</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-lg hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC]">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] overflow-hidden transition-all duration-300">
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-[#4F46E5] to-[#6366F1] px-8 py-8">
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}?v={{ time() }}" class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-xl" alt="{{ $user->name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=4F46E5&color=fff&size=128" class="w-24 h-24 rounded-full ring-4 ring-white shadow-xl" alt="{{ $user->name }}">
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 {{ $user->active_status ? 'bg-emerald-500' : 'bg-slate-400' }} border-4 border-white rounded-full"></div>
                        </div>
                        <div class="flex-1 text-white">
                            <h3 class="text-2xl font-bold mb-1">{{ $user->name }}</h3>
                            <p class="text-white/80 mb-3">{{ $user->email }}</p>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm
                                    @switch($user->role)
                                        @case('admin')
                                            text-white
                                            @break
                                        @case('hrd')
                                            text-white
                                            @break
                                        @case('division_leader')
                                            text-white
                                            @break
                                        @case('user')
                                            text-white
                                            @break
                                        @default
                                            text-white
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                                <span class="px-3 py-1.5 inline-flex text-xs font-semibold rounded-full {{ $user->active_status ? 'bg-emerald-500/20 text-emerald-100' : 'bg-rose-500/20 text-rose-100' }}">
                                    {{ $user->active_status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-[#6B7280] uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Basic Information
                                </h4>
                                <div class="space-y-4">
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Full Name</p>
                                        <p class="text-base font-semibold text-slate-900">{{ $user->name }}</p>
                                    </div>
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Email Address</p>
                                        <p class="text-base font-semibold text-slate-900">{{ $user->email }}</p>
                                    </div>
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Role</p>
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                                            @switch($user->role)
                                                @case('admin')
                                                    bg-purple-50 text-purple-700 ring-1 ring-purple-200
                                                    @break
                                                @case('hrd')
                                                    bg-blue-50 text-blue-700 ring-1 ring-blue-200
                                                    @break
                                                @case('division_leader')
                                                    bg-amber-50 text-amber-700 ring-1 ring-amber-200
                                                    @break
                                                @case('user')
                                                    bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                                                    @break
                                                @default
                                                    bg-slate-50 text-slate-700 ring-1 ring-slate-200
                                            @endswitch">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </div>
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Status</p>
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full {{ $user->active_status ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' }}">
                                            {{ $user->active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Information -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-[#6B7280] uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Work Information
                                </h4>
                                <div class="space-y-4">
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Division</p>
                                        <p class="text-base font-semibold text-slate-900">
                                            {{ $user->division ? $user->division->name : 'No Division' }}
                                        </p>
                                    </div>
                                    @if($user->division && $user->division->leader)
                                        <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                            <p class="text-xs font-medium text-[#6B7280] mb-1">Division Leader</p>
                                            <p class="text-base font-semibold text-slate-900">{{ $user->division->leader->name }}</p>
                                        </div>
                                    @endif
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Leave Quota</p>
                                        <p class="text-base font-semibold text-slate-900">{{ $user->leave_quota }} days</p>
                                    </div>
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Join Date</p>
                                        <p class="text-base font-semibold text-slate-900">
                                            {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : 'Not set' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    @if($user->phone || $user->address)
                        <div class="mt-8 pt-8 border-t border-[#E5E7EB]">
                            <h4 class="text-sm font-semibold text-[#6B7280] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Contact Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($user->phone)
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Phone</p>
                                        <p class="text-base font-semibold text-slate-900">{{ $user->phone }}</p>
                                    </div>
                                @endif
                                @if($user->address)
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB] md:col-span-2">
                                        <p class="text-xs font-medium text-[#6B7280] mb-1">Address</p>
                                        <p class="text-base font-semibold text-slate-900">{{ $user->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Account Information -->
                    <div class="mt-8 pt-8 border-t border-[#E5E7EB]">
                        <h4 class="text-sm font-semibold text-[#6B7280] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Account Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                <p class="text-xs font-medium text-[#6B7280] mb-1">Account Created</p>
                                <p class="text-base font-semibold text-slate-900">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</p>
                            </div>
                            <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E5E7EB]">
                                <p class="text-xs font-medium text-[#6B7280] mb-1">Last Updated</p>
                                <p class="text-base font-semibold text-slate-900">{{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

