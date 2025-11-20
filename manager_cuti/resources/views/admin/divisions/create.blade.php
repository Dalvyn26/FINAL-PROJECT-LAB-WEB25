<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Create Division') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 transition-all">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Add New Division</h3>

                @if($errors->any())
                    <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.divisions.store') }}">
                    @csrf

                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-slate-800 mb-3">Division Information</h4>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                   class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-slate-800 mb-3">Division Leader</h4>
                        
                        <div class="mb-4">
                            <label for="leader_id" class="block text-sm font-medium text-slate-700 mb-1">Leader</label>
                            <select name="leader_id" id="leader_id" 
                                    class="w-full px-3 py-2 bg-gray-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Leader</option>
                                @foreach($leaders as $leader)
                                    <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                        {{ $leader->name }} ({{ $leader->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-slate-500">Select a user with role 'division_leader' to assign as division leader</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.divisions.index') }}" class="mr-4 bg-slate-500 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Create Division
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>