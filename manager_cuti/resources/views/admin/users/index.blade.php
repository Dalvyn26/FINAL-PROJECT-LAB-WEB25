<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Users</h3>
                    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                        Add User
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Filter Toolbar -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name/Email..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-600 mb-1">Role</label>
                            <select name="role" id="role" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                <option value="division_leader" {{ request('role') == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <label for="division_id" class="block text-sm font-medium text-slate-600 mb-1">Division</label>
                            <select name="division_id" id="division_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Divisions</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tenure Filter -->
                        <div>
                            <label for="tenure" class="block text-sm font-medium text-slate-600 mb-1">Tenure</label>
                            <select name="tenure" id="tenure" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Tenures</option>
                                <option value="<1" {{ request('tenure') == '<1' ? 'selected' : '' }}>&lt; 1 Year</option>
                                <option value="1-3" {{ request('tenure') == '1-3' ? 'selected' : '' }}>1-3 Years</option>
                                <option value=">3" {{ request('tenure') == '>3' ? 'selected' : '' }}>&gt; 3 Years</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Sort By -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-slate-600 mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="name" {{ request('sort') == 'name' && request('direction') != 'desc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ (request('sort') == 'name' && request('direction') == 'desc') || request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="division" {{ request('sort') == 'division' && request('direction') != 'desc' ? 'selected' : '' }}>Division (A-Z)</option>
                                <option value="division_desc" {{ (request('sort') == 'division' && request('direction') == 'desc') || request('sort') == 'division_desc' ? 'selected' : '' }}>Division (Z-A)</option>
                                <option value="join_date" {{ request('sort') == 'join_date' && request('direction') != 'desc' ? 'selected' : '' }}>Join Date (Oldest)</option>
                                <option value="join_date_desc" {{ (request('sort') == 'join_date' && request('direction') == 'desc') || request('sort') == 'join_date_desc' ? 'selected' : '' }}>Join Date (Latest)</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all text-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-indigo-700 transition-colors">
                                        Name
                                        @if(request('sort') == 'name')
                                            <svg class="w-4 h-4 ml-1 {{ request('direction') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'division', 'direction' => (request('sort') == 'division' && request('direction') == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center hover:text-indigo-700 transition-colors">
                                        Division
                                        @if(request('sort') == 'division')
                                            <svg class="w-4 h-4 ml-1 {{ request('direction') == 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'join_date', 'direction' => (request('sort') == 'join_date' && request('direction') == 'desc') ? 'asc' : 'desc']) }}" class="flex items-center hover:text-indigo-700 transition-colors">
                                        Join Date
                                        @if(request('sort') == 'join_date')
                                            <svg class="w-4 h-4 ml-1 {{ request('direction') == 'desc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-700 font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs font-bold rounded-full 
                                            @switch($user->role)
                                                @case('admin')
                                                    bg-purple-100 text-purple-800
                                                    @break
                                                @case('hrd')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case('division_leader')
                                                    bg-amber-100 text-amber-800
                                                    @break
                                                @case('user')
                                                    bg-emerald-100 text-emerald-800
                                                    @break
                                                @default
                                                    bg-slate-100 text-slate-800
                                            @endswitch">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->division ? $user->division->name : 'None' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs font-bold rounded-full
                                            {{ $user->active_status ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $user->active_status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-500 hover:text-amber-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>