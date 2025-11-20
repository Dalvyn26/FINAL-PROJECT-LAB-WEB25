<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Division Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Divisions</h3>
                    <a href="{{ route('admin.divisions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                        Add Division
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Leader</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Members</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($divisions as $division)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">{{ $division->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $division->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        @if($division->leader)
                                            {{ $division->leader->name }}
                                        @else
                                            <span class="text-slate-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $division->users_count }} members
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.divisions.edit', $division) }}" class="text-amber-500 hover:text-amber-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 transition" onclick="return confirm('Are you sure you want to delete this division?')">
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