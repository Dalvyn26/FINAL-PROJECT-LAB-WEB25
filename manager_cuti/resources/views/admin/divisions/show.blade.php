<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-slate-800 leading-tight">
                    Division Details
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">View and manage division members</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Division Info Card -->
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden mb-8 transition-all duration-300 animate-fade-up">
                <!-- Division Header with Gradient Banner -->
                <div class="relative bg-gradient-to-r from-indigo-500/10 via-indigo-500/5 to-transparent px-6 py-5 border-b border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">{{ $division->name }}</h3>
                                    <p class="text-sm text-slate-500 mt-1">{{ $division->description ?: 'No description provided' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                            <a href="{{ route('admin.divisions.edit', $division) }}" class="w-full sm:w-auto group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-4 sm:px-5 rounded-full transition-all duration-200 hover:shadow-lg hover:scale-105 text-sm sm:text-base">
                                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Edit Division
                            </a>
                            <a href="{{ route('admin.divisions.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-4 sm:px-5 rounded-full transition-all duration-200 hover:shadow-md text-sm sm:text-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Division Leader Card -->
                <div class="px-6 py-5">
                    <h4 class="text-sm font-semibold text-slate-600 mb-4 uppercase tracking-wider">Division Leader</h4>
                    
                    @if($division->leader)
                        <div class="bg-gradient-to-r from-amber-50 to-amber-50/50 border border-amber-200/60 rounded-2xl p-5 animate-fade-up" style="animation-delay: 100ms;">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    @if($division->leader->avatar)
                                        <img src="{{ Storage::url($division->leader->avatar) }}?v={{ time() }}" class="w-16 h-16 rounded-full object-cover ring-4 ring-white shadow-lg" alt="{{ $division->leader->name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($division->leader->name ?? 'User') }}&background=F59E0B&color=fff" class="w-16 h-16 rounded-full ring-4 ring-white shadow-lg" alt="{{ $division->leader->name }}">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="font-bold text-slate-900 text-lg">{{ $division->leader->name }}</div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-amber-100 to-amber-50 text-amber-700 ring-1 ring-amber-200">
                                            Division Leader
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-600">{{ $division->leader->email }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                            <p class="text-sm text-slate-500 italic">No leader assigned to this division</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Add Member Form -->
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl p-6 mb-8 transition-all duration-300 animate-fade-up" style="animation-delay: 150ms;">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Add Member to Division</h3>
                
                @if(session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 animate-fade-up">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
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

                @if($availableUsers->count() > 0)
                    <form method="POST" action="{{ route('admin.divisions.members.store', $division) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="user_id" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Select User</label>
                                <div class="relative">
                                    <select name="user_id" id="user_id" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">Select User to Add</option>
                                        @foreach($availableUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 hover:shadow-lg hover:scale-105">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Member
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                            <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-1">All employees assigned</h3>
                        <p class="text-sm text-slate-500">All employees are already assigned to divisions.</p>
                    </div>
                @endif
            </div>

            <!-- Division Members Table -->
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 animate-fade-up" style="animation-delay: 200ms;">
                <!-- Table Header -->
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <h3 class="text-lg font-bold text-slate-800">Division Members</h3>
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-gradient-to-r from-indigo-100 to-indigo-50 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                            {{ $members->total() }} member{{ $members->total() != 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>

                @if($members->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($members as $index => $member)
                                    <tr class="hover:bg-indigo-50/30 transition-all duration-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    @if($member->avatar)
                                                        <img src="{{ Storage::url($member->avatar) }}?v={{ time() }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $member->name }}">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name ?? 'User') }}&background=4F46E5&color=fff" class="w-11 h-11 rounded-full ring-2 ring-white shadow-sm" alt="{{ $member->name }}">
                                                    @endif
                                                </div>
                                                <div class="font-semibold text-slate-900">{{ $member->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="text-sm text-slate-600">{{ $member->email }}</span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                @switch($member->role)
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
                                                {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $member->active_status ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200 shadow-sm shadow-emerald-200/50' : 'bg-rose-100 text-rose-700 ring-1 ring-rose-200' }}">
                                                {{ $member->active_status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <form action="{{ route('admin.divisions.members.destroy', ['division' => $division, 'user' => $member]) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin mengeluarkan user ini dari divisi?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="group p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all duration-200 hover:scale-110">
                                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden px-6 pb-6 space-y-4 pt-6">
                        @foreach($members as $member)
                            <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            @if($member->avatar)
                                                <img src="{{ Storage::url($member->avatar) }}?v={{ time() }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-sm" alt="{{ $member->name }}">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name ?? 'User') }}&background=4F46E5&color=fff" class="w-12 h-12 rounded-full ring-2 ring-white shadow-sm" alt="{{ $member->name }}">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-slate-900 truncate">{{ $member->name }}</div>
                                            <div class="text-xs text-slate-500 truncate mt-0.5">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.divisions.members.destroy', ['division' => $division, 'user' => $member]) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin mengeluarkan user ini dari divisi?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        @switch($member->role)
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
                                        {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $member->active_status ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-1 ring-rose-200' }}">
                                        {{ $member->active_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/30">
                        {{ $members->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">No members</h3>
                            <p class="text-sm text-slate-500">There are no members in this division yet.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>