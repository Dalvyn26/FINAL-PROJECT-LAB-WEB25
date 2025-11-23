<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('profile.update', $user) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                @method('PATCH')

                <!-- Left Column: Profile Card & Leave Quota -->
                <div class="lg:col-span-1">
                    <!-- Profile Card -->
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all" x-data="{ photoName: null, photoPreview: null }">
                        <div class="text-center">
                            <div class="flex justify-center mb-4">
                                <div class="relative">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" alt="Preview Avatar" class="w-24 h-24 rounded-full border-4 border-slate-200 object-cover">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <?php
                                            $avatarUrl = $user->avatar 
                                                ? Storage::url($user->avatar) . '?v=' . time() 
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0ea5e9&color=fff';
                                        ?>
                                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-24 h-24 rounded-full border-4 border-slate-200 object-cover">
                                    </template>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                            <p class="text-slate-600">{{ $user->email }}</p>
                            
                            <div class="mt-4">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
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
                            </div>
                            
                            <div class="mt-4 text-sm text-slate-600">
                                @if($user->phone)
                                    <p><strong>Phone:</strong> {{ $user->phone }}</p>
                                @endif
                                @if($user->address)
                                    <p class="mt-1"><strong>Address:</strong> {{ $user->address }}</p>
                                @endif
                                <p class="mt-1"><strong>Joined:</strong> 
                                    @if($user->join_date)
                                        {{ \Carbon\Carbon::parse($user->join_date)->format('d M Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leave Quota Card (for user and division_leader) -->
                    @if(in_array($user->role, ['user', 'division_leader']))
                        <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 mt-6 transition-all">
                            <h4 class="text-lg font-semibold text-slate-800 mb-4">Leave Quota Info</h4>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <!-- Total Quota -->
                                <div class="text-center bg-slate-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-slate-600">Total</p>
                                    <p class="text-xl font-bold text-slate-800">{{ $totalQuota }} days</p>
                                </div>
                                
                                <!-- Used Days -->
                                <div class="text-center bg-amber-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-slate-600">Used</p>
                                    <p class="text-xl font-bold text-slate-800">{{ $usedQuota }} days</p>
                                </div>
                                
                                <!-- Remaining Days -->
                                <div class="text-center bg-{{ $remainingQuota <= 2 ? 'rose' : 'emerald' }}-50 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-slate-600">Remaining</p>
                                    <p class="text-xl font-bold text-{{ $remainingQuota <= 2 ? 'rose' : 'emerald' }}-700">{{ $remainingQuota }} days</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="w-full bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-{{ $remainingQuota <= 2 ? 'rose' : 'emerald' }}-600 h-2.5 rounded-full" style="width: {{ (($totalQuota - $remainingQuota) / $totalQuota) * 100 }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-slate-500 mt-1">
                                    <span>0</span>
                                    <span>{{ $totalQuota - $remainingQuota }}/{{ $totalQuota }} used</span>
                                    <span>{{ $totalQuota }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Edit Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Edit Profile Information</h3>

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

                        <!-- Name & Email (Admin only editable) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       @if($user->role !== 'admin') disabled @endif
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                              {{ $user->role !== 'admin' ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $user->role !== 'admin' ? 'Field is readonly for non-admin users' : 'Your full name' }}
                                </p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       @if($user->role !== 'admin') disabled @endif
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                              {{ $user->role !== 'admin' ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $user->role !== 'admin' ? 'Field is readonly for non-admin users' : 'Your email address' }}
                                </p>
                            </div>
                        </div>

                        <!-- Username & Role (Admin only editable) -->
                        @if($user->role === 'admin')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->name) }}" 
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                                    <select name="role" id="role" 
                                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="hrd" {{ $user->role == 'hrd' ? 'selected' : '' }}>HRD</option>
                                        <option value="division_leader" {{ $user->role == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Phone & Address -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <!-- Avatar Upload (for non-admin users) -->
                        @if($user->role !== 'admin')
                            <div class="mb-4">
                                <label for="avatar" class="block text-sm font-medium text-slate-700 mb-1">Upload Avatar</label>
                                @if($user->avatar)
                                    <p class="text-sm text-slate-600 mb-2">
                                        Current file: <a href="{{ Storage::url($user->avatar) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">View Current Avatar</a>
                                    </p>
                                @endif
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50"
                                     @dragover.prevent="$event.target.classList.add('border-indigo-500')"
                                     @dragleave.prevent="$event.target.classList.remove('border-indigo-500')"
                                     @drop.prevent="
                                         $event.target.classList.remove('border-indigo-500'); 
                                         document.getElementById('avatar').files = $event.dataTransfer.files; 
                                         $event.target.querySelector('input[type=file]').dispatchEvent(new Event('change'));
                                     ">
                                    <div class="space-y-1 text-center w-full">
                                        <template x-if="!photoName">
                                            <div>
                                                <div class="flex text-sm text-slate-600 justify-center">
                                                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex text-sm text-slate-600 justify-center">
                                                    <label for="avatar" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                                                        <span>Click to upload profile picture</span>
                                                        <input id="avatar" name="avatar" type="file" accept="image/*"
                                                               class="sr-only"
                                                               x-ref="photoFile"
                                                               @change="
                                                                   const file = $refs.photoFile.files[0]; 
                                                                   photoName = file ? file.name : null; 
                                                                   const reader = new FileReader(); 
                                                                   reader.onload = e => photoPreview = e.target.result; 
                                                                   if(file) { reader.readAsDataURL(file); }
                                                               ">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-slate-500">
                                                    PNG, JPG, GIF up to 3MB
                                                </p>
                                            </div>
                                        </template>
                                        <template x-if="photoName">
                                            <div class="flex flex-col items-center">
                                                <div class="flex text-sm text-emerald-600 justify-center mb-2">
                                                    <svg class="mx-auto h-12 w-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-slate-700" x-text="photoName"></p>
                                                <p class="text-xs text-emerald-600">File selected successfully!</p>
                                            </div>
                                        </template>
                                        @error('avatar')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Password Change -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New Password (Optional)</label>
                            <input type="password" name="password" id="password" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-xs text-slate-500">Leave blank to keep current password</p>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-all hover:-translate-y-0.5 shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Section -->
    @if($user->role === 'admin' && $user->id === auth()->id())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Delete Account</h3>
                    <p class="text-sm text-slate-600 mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Before deleting your account, please download any data or information that you wish to retain.
                    </p>

                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    >
                        Delete Account
                    </x-danger-button>
                </div>
            </div>

            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('profile.destroy', $user) }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-slate-900">Are you sure your want to delete your account?</h2>

                    <p class="mt-1 text-sm text-slate-600">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="Password" class="sr-only" />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Password"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    @endif
</x-app-layout>