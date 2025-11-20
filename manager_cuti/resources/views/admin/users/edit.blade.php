<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Edit User: {{ $user->name }}</h3>

                @if($errors->any())
                    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-slate-800 mb-3">User Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="password" 
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-slate-800 mb-3">User Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role *</label>
                                <select name="role" id="role" required 
                                        class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="hrd" {{ old('role', $user->role) == 'hrd' ? 'selected' : '' }}>HRD</option>
                                    <option value="division_leader" {{ old('role', $user->role) == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="division_id" class="block text-sm font-medium text-slate-700 mb-1">Division</label>
                                <select name="division_id" id="division_id" 
                                        class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="leave_quota" class="block text-sm font-medium text-slate-700 mb-1">Leave Quota *</label>
                                <input type="number" name="leave_quota" id="leave_quota" value="{{ old('leave_quota', $user->leave_quota) }}" required min="0" max="365"
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="active_status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                <select name="active_status" id="active_status" 
                                        class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1" {{ old('active_status', $user->active_status) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !old('active_status', $user->active_status) ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-slate-800 mb-3">Contact Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="join_date" class="block text-sm font-medium text-slate-700 mb-1">Join Date</label>
                                <input type="date" name="join_date" id="join_date" value="{{ old('join_date', $user->join_date) }}"
                                       class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                            <textarea name="address" id="address" rows="2"
                                      class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.users.index') }}" class="mr-4 bg-slate-500 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>