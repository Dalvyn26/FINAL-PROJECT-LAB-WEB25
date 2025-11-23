<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Division Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Division Info Card -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 mb-8 transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">{{ $division->name }}</h3>
                        <p class="text-slate-600 mt-1">{{ $division->description ?: 'No description provided' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.divisions.edit', $division) }}" class="bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Edit Division
                        </a>
                        <a href="{{ route('admin.divisions.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Back to List
                        </a>
                    </div>
                </div>

                <!-- Division Leader Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <h4 class="text-md font-semibold text-slate-700 mb-3">Division Leader</h4>
                    
                    @if($division->leader)
                        <div class="flex items-center gap-4">
                            <x-avatar :user="$division->leader" classes="w-12 h-12" />
                            <div>
                                <div class="font-medium text-slate-800">{{ $division->leader->name }}</div>
                                <div class="text-sm text-slate-500">{{ $division->leader->email }}</div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">No leader assigned to this division</p>
                    @endif
                </div>
            </div>

            <!-- Add Member Form -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 mb-8 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Add Member to Division</h3>
                
                @if(session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('status') }}
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

                @if($availableUsers->count() > 0)
                    <form method="POST" action="{{ route('admin.divisions.members.store', $division) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1">Select User</label>
                                <select name="user_id" id="user_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select User to Add</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium w-full py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                    Add Member
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4">
                        <p class="text-slate-500">All employees are already assigned to divisions.</p>
                    </div>
                @endif
            </div>

            <!-- Division Members Table -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Division Members</h3>
                    <p class="text-sm text-slate-500">{{ $members->total() }} member(s)</p>
                </div>

                @if($members->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($members as $member)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <div class="flex items-center gap-3">
                                                <x-avatar :user="$member" classes="w-10 h-10" />
                                                <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle text-sm text-slate-600">
                                            {{ $member->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
                                                @switch($member->role)
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
                                                {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                                            <span class="px-2 inline-flex text-xs font-bold rounded-full
                                                {{ $member->active_status ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                {{ $member->active_status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-middle text-sm">
                                            <form action="{{ route('admin.divisions.members.destroy', ['division' => $division, 'user' => $member]) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin mengeluarkan user ini dari divisi?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <div class="mt-6">
                        {{ $members->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No members</h3>
                        <p class="mt-1 text-sm text-slate-500">There are no members in this division yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>