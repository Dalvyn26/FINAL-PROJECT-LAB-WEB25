<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight tracking-tight">
                    {{ __('Profile') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage your account settings and preferences</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('profile.update', $user) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                @method('PATCH')

                <!-- Left Column: Profile Card & Leave Quota -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-8 transition-all duration-300 hover:shadow-[0_4px_12px_0_rgba(0,0,0,0.08)]" 
                         x-data="{ photoName: null, photoPreview: null }">
                        <div class="text-center">
                            <div class="flex justify-center mb-6">
                                <div class="relative">
                                    <div class="relative">
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" alt="Preview Avatar" class="w-32 h-32 rounded-full object-cover ring-4 ring-white shadow-xl">
                                        </template>
                                        <template x-if="!photoPreview">
                                            <?php
                                                $avatarUrl = $user->avatar 
                                                    ? Storage::url($user->avatar) . '?v=' . time() 
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=fff&size=128';
                                            ?>
                                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover ring-4 ring-white shadow-xl">
                                        </template>
                                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[#4F46E5] to-[#6366F1] opacity-0 hover:opacity-20 transition-opacity duration-300"></div>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-10 h-10 bg-gradient-to-br from-[#4F46E5] to-[#6366F1] rounded-full flex items-center justify-center shadow-lg ring-4 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $user->name }}</h3>
                            <p class="text-[#6B7280] text-sm mb-4">{{ $user->email }}</p>
                            
                            <div class="mb-6">
                                <span class="px-4 py-1.5 inline-flex text-xs font-semibold rounded-full 
                                    @switch($user->role)
                                        @case('admin')
                                            bg-purple-100 text-purple-700 ring-1 ring-purple-200
                                            @break
                                        @case('hrd')
                                            bg-red-100 text-red-700 ring-1 ring-red-200
                                            @break
                                        @case('division_leader')
                                            bg-amber-100 text-amber-700 ring-1 ring-amber-200
                                            @break
                                        @case('user')
                                            bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200
                                            @break
                                        @default
                                            bg-slate-100 text-slate-700 ring-1 ring-slate-200
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                            
                            <div class="space-y-3 text-sm text-[#6B7280] border-t border-[#E5E7EB] pt-6">
                                @if($user->phone)
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="font-medium text-slate-700">{{ $user->phone }}</span>
                                    </div>
                                @endif
                                @if($user->address)
                                    <div class="flex items-start justify-center gap-2">
                                        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-center">{{ $user->address }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-slate-700">
                                        Joined: 
                                        @if($user->join_date)
                                            {{ \Carbon\Carbon::parse($user->join_date)->format('d M Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leave Quota Card (for user and division_leader) -->
                    @if(in_array($user->role, ['user', 'division_leader']))
                        <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300">
                            <h4 class="text-lg font-semibold text-slate-900 mb-6">Leave Quota Info</h4>
                            
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <!-- Total Quota -->
                                <div class="text-center bg-slate-50 rounded-xl p-4 border border-[#E5E7EB]">
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Total</p>
                                    <p class="text-2xl font-bold text-slate-900">{{ $totalQuota }}</p>
                                    <p class="text-xs text-[#6B7280] mt-1">days</p>
                                </div>
                                
                                <!-- Used Days -->
                                <div class="text-center bg-amber-50 rounded-xl p-4 border border-amber-200">
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Used</p>
                                    <p class="text-2xl font-bold text-amber-700">{{ $usedQuota }}</p>
                                    <p class="text-xs text-[#6B7280] mt-1">days</p>
                                </div>
                                
                                <!-- Remaining Days -->
                                <div class="text-center rounded-xl p-4 border {{ $remainingQuota <= 2 ? 'bg-rose-50 border-rose-200' : 'bg-emerald-50 border-emerald-200' }}">
                                    <p class="text-xs font-semibold text-[#6B7280] mb-1">Remaining</p>
                                    <p class="text-2xl font-bold {{ $remainingQuota <= 2 ? 'text-rose-700' : 'text-emerald-700' }}">{{ $remainingQuota }}</p>
                                    <p class="text-xs text-[#6B7280] mt-1">days</p>
                                </div>
                            </div>
                            
                            <div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 mb-2">
                                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $remainingQuota <= 2 ? 'bg-rose-600' : 'bg-emerald-600' }}" style="width: {{ (($totalQuota - $remainingQuota) / $totalQuota) * 100 }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-[#6B7280]">
                                    <span>0</span>
                                    <span class="font-medium">{{ $totalQuota - $remainingQuota }}/{{ $totalQuota }} used</span>
                                    <span>{{ $totalQuota }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Edit Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-8 transition-all duration-300" 
                         x-data="{ photoName: null, photoPreview: null }">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 rounded-xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1]">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">Edit Profile Information</h3>
                        </div>

                        @if(session('status'))
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Name & Email (Admin only editable) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       @if($user->role !== 'admin') disabled @endif
                                       class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200
                                              {{ $user->role !== 'admin' ? 'bg-slate-50 cursor-not-allowed' : 'hover:border-[#6B7280]' }}">
                                <p class="mt-2 text-xs text-[#6B7280]">
                                    {{ $user->role !== 'admin' ? 'Field is readonly for non-admin users' : 'Your full name' }}
                                </p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                       @if($user->role !== 'admin') disabled @endif
                                       class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200
                                              {{ $user->role !== 'admin' ? 'bg-slate-50 cursor-not-allowed' : 'hover:border-[#6B7280]' }}">
                                <p class="mt-2 text-xs text-[#6B7280]">
                                    {{ $user->role !== 'admin' ? 'Field is readonly for non-admin users' : 'Your email address' }}
                                </p>
                            </div>
                        </div>

                        <!-- Username & Role (Admin only editable) -->
                        @if($user->role === 'admin')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->name) }}" 
                                           class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                                    <select name="role" id="role" 
                                            class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="hrd" {{ $user->role == 'hrd' ? 'selected' : '' }}>HRD</option>
                                        <option value="division_leader" {{ $user->role == 'division_leader' ? 'selected' : '' }}>Division Leader</option>
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Phone & Address -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280] resize-none">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <!-- Avatar Upload (for non-admin users) -->
                        @if($user->role !== 'admin')
                            <div class="mb-6">
                                <label for="avatar" class="block text-sm font-semibold text-slate-700 mb-2">Upload Avatar</label>
                                @if($user->avatar)
                                    <p class="text-sm text-[#6B7280] mb-3">
                                        Current file: <a href="{{ Storage::url($user->avatar) }}" target="_blank" class="text-[#4F46E5] hover:text-[#6366F1] font-medium">View Current Avatar</a>
                                    </p>
                                @endif
                                <div class="mt-1 border-2 border-dashed border-[#E5E7EB] rounded-xl p-8 text-center cursor-pointer hover:bg-[#F8FAFC] hover:border-[#4F46E5]/40 transition-all duration-300 relative group"
                                     @click="
                                         const photoInput = document.getElementById('avatar');
                                         if (photoInput) {
                                             photoInput.click();
                                         }
                                     "
                                     @dragover.prevent="$event.target.classList.add('border-[#4F46E5]', 'bg-indigo-50/50')"
                                     @dragleave.prevent="$event.target.classList.remove('border-[#4F46E5]', 'bg-indigo-50/50')"
                                     @drop.prevent="
                                         $event.target.classList.remove('border-[#4F46E5]', 'bg-indigo-50/50'); 
                                         $refs.photo.files = $event.dataTransfer.files; 
                                         $refs.photo.dispatchEvent(new Event('change'));
                                     ">
                                    <input type="file" 
                                           name="avatar" 
                                           id="avatar"
                                           class="hidden" 
                                           x-ref="photo" 
                                           accept="image/*"
                                           @change="
                                               if ($refs.photo.files && $refs.photo.files[0]) {
                                                   photoName = $refs.photo.files[0].name;
                                                   const reader = new FileReader();
                                                   reader.onload = (e) => { photoPreview = e.target.result; };
                                                   reader.readAsDataURL($refs.photo.files[0]);
                                               }
                                           ">
                                    
                                    <!-- State 1: Belum Ada File (Default) -->
                                    <div x-show="!photoName" class="space-y-3 pointer-events-none">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] mb-2 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700">Click to upload profile picture</p>
                                        <p class="text-xs text-[#6B7280]">PNG, JPG, GIF up to 3MB</p>
                                    </div>
                                    
                                    <!-- State 2: File Terpilih (Success) -->
                                    <div x-show="photoName" class="space-y-3 pointer-events-none">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-100 mb-2">
                                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700" x-text="photoName"></p>
                                        <p class="text-xs text-emerald-600 font-medium">File selected successfully</p>
                                    </div>
                                    
                                    @error('avatar')
                                        <p class="mt-3 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Password Change -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">New Password (Optional)</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                            <p class="mt-2 text-xs text-[#6B7280]">Leave blank to keep current password</p>
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-[#E5E7EB]">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4F46E5] to-[#6366F1] hover:from-[#4338CA] hover:to-[#4F46E5] text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
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
            <div class="mt-8 bg-white/80 backdrop-blur-sm border border-rose-200 shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-6 transition-all duration-300">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Delete Account</h3>
                    <p class="text-sm text-[#6B7280] mb-6">
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Before deleting your account, please download any data or information that you wish to retain.
                    </p>

                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="rounded-xl"
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

                    <p class="mt-1 text-sm text-[#6B7280]">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="Password" class="sr-only" />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-3/4 rounded-xl"
                            placeholder="Password"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')" class="rounded-xl">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3 rounded-xl">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    @endif
</x-app-layout>
