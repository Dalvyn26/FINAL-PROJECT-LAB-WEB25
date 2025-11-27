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
                    Create Division
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Add a new division to your organization</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('admin.divisions.store') }}">
                        @csrf

                        <!-- Division Information Section -->
                        <div class="mb-8">
                            <h4 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-200 flex items-center gap-2 animate-fade-up">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Division Information
                            </h4>
                            
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                                    Division Name
                                    <span class="text-rose-500 ml-1">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                       placeholder="Enter division name"
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
                                <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                                <textarea name="description" id="description" rows="4" 
                                          placeholder="Enter division description (optional)"
                                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all resize-none">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Division Leader Assignment Section -->
                        <div class="mb-8">
                            <h4 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-200 flex items-center gap-2 animate-fade-up" style="animation-delay: 100ms;">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Division Leader Assignment
                            </h4>
                            
                            <div class="mb-6 animate-fade-up" style="animation-delay: 150ms;">
                                <label for="leader_id" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M5.935 6.5l1.5 1.5m0 0l1.5-1.5m-1.5 1.5L5.935 6.5m0 0L4.5 5.065M5.935 6.5l1.5 1.5m0 0l1.5-1.5m-1.5 1.5L5.935 6.5m0 0L4.5 5.065M5.935 6.5l1.5 1.5m0 0l1.5-1.5m-1.5 1.5L5.935 6.5m0 0L4.5 5.065"></path>
                                    </svg>
                                    Ketua Divisi
                                    <span class="text-rose-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select name="leader_id" id="leader_id" required
                                            class="w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="">Pilih Ketua Divisi</option>
                                        @forelse($availableLeaders as $leader)
                                            <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                                {{ $leader->name }} ({{ $leader->email }})
                                            </option>
                                        @empty
                                            <option value="">No available leaders</option>
                                        @endforelse
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Hanya division leader yang belum menjadi ketua divisi lain yang dapat dipilih.</p>
                                @error('leader_id')
                                    <p class="mt-2 text-sm text-rose-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ route('admin.divisions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-6 rounded-full transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-2.5 px-6 rounded-full transition-all duration-200 hover:shadow-lg hover:scale-105">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Division
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>