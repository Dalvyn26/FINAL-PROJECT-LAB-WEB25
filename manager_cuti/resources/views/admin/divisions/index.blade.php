<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Division Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Divisions</h3>
                    <a href="{{ route('admin.divisions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                        Add Division
                    </a>
                </div>

                <!-- Filter Toolbar -->
                <form method="GET" action="{{ route('admin.divisions.index') }}" class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search division or leader..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-slate-600 mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="created_newest" {{ request('sort') == 'created_newest' ? 'selected' : '' }}>Created Newest</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="members_most" {{ request('sort') == 'members_most' ? 'selected' : '' }}>Members Most</option>
                                <option value="members_least" {{ request('sort') == 'members_least' ? 'selected' : '' }}>Members Least</option>
                                <option value="created_oldest" {{ request('sort') == 'created_oldest' ? 'selected' : '' }}>Created Oldest</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                Filter
                            </button>
                            <a href="{{ route('admin.divisions.index') }}" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all text-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Leader</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Members</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($divisions as $division)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="text-sm font-semibold text-slate-900">{{ $division->name }}</div>
                                        @if($division->description)
                                            <div class="text-sm text-slate-500">{{ Str::limit($division->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        @if($division->leader)
                                            <div class="flex items-center gap-3">
                                                <x-avatar :user="$division->leader" classes="w-10 h-10" />
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $division->leader->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $division->leader->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-400 italic">No Leader</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle text-sm text-slate-600">
                                        {{ $division->users_count }} member{{ $division->users_count != 1 ? 's' : '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle text-sm">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.divisions.show', $division) }}" class="text-blue-600 hover:text-blue-900 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.divisions.edit', $division) }}" class="text-amber-600 hover:text-amber-900 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin? Semua anggota di divisi ini akan dikeluarkan (menjadi Non-Divisi).">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">
                                        No divisions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $divisions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>