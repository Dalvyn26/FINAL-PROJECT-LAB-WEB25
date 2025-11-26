<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-slate-800 leading-tight">
                {{ __('Create Holiday') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 transition-all">
                <div class="text-slate-700">
                    <h3 class="text-lg font-semibold text-slate-800 mb-6">Add New Holiday</h3>

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.holidays.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-slate-800 mb-3">Holiday Information</h4>
                            
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                                    Holiday Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="e.g., Hari Raya Idul Fitri">
                                @error('title')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="holiday_date" class="block text-sm font-medium text-slate-700 mb-1">
                                    Holiday Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="holiday_date" id="holiday_date" value="{{ old('holiday_date') }}" required 
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('holiday_date')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Optional description about this holiday">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.holidays.index') }}" class="mr-4 bg-slate-300 hover:bg-slate-400 text-slate-800 font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                Create Holiday
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

