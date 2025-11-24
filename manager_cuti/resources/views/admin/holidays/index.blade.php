<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Holiday Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="holidayModal()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <!-- Header Section with Action Buttons -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Hari Libur</h3>
                    <div class="flex gap-3">
                        <!-- Sync Google Calendar Button -->
                        <form action="{{ route('admin.holidays.sync') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5 flex items-center gap-2"
                                    title="Import libur nasional otomatis">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12m0 0l4-4m-4 4l-4 4"></path>
                                </svg>
                                Sync Google Calendar
                            </button>
                        </form>
                        <!-- Add Holiday Button -->
                        <button @click="openModal()" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                            Add Holiday
                        </button>
                    </div>
                </div>

                <!-- Filter Toolbar -->
                <form method="GET" action="{{ route('admin.holidays.index') }}" class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search holiday name..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="filter" class="block text-sm font-medium text-slate-600 mb-1">Filter</label>
                            <select name="filter" id="filter" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Holidays</option>
                                <option value="national" {{ request('filter') == 'national' ? 'selected' : '' }}>National Holidays</option>
                                <option value="manual" {{ request('filter') == 'manual' ? 'selected' : '' }}>Manual Holidays</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-slate-600 mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="holiday_date_asc" {{ request('sort') == 'holiday_date_asc' ? 'selected' : '' }}>Date (Ascending)</option>
                                <option value="holiday_date_desc" {{ request('sort') == 'holiday_date_desc' ? 'selected' : '' }}>Date (Descending)</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                Filter
                            </button>
                            <a href="{{ route('admin.holidays.index') }}" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all text-center">
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

                <!-- Table Data Libur -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hari</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Libur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($holidays as $holiday)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $holiday->holiday_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="text-sm text-slate-600">
                                            {{ $holiday->holiday_date->translatedFormat('l') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="text-sm font-medium text-slate-900">{{ $holiday->title }}</div>
                                        @if($holiday->description)
                                            <div class="text-xs text-slate-500 mt-1">{{ Str::limit($holiday->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        @if($holiday->is_national_holiday)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                National Holiday
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Manual/Company
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle text-sm">
                                        <div class="flex space-x-3">
                                            <button @click="openModal({{ $holiday->id }}, '{{ $holiday->title }}', '{{ $holiday->holiday_date->format('Y-m-d') }}', '{{ addslashes($holiday->description) }}')" 
                                                    class="text-amber-600 hover:text-amber-900 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline" data-confirm-delete="true" data-confirm-message="Apakah Anda yakin ingin menghapus hari libur ini?">
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
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                        No holidays found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Create/Edit (Shared Modal with Alpine.js) -->
        <div x-show="isOpen" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal()"></div>
            
            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative z-50"
                     @click.away="closeModal()"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-slate-800" x-text="isEdit ? 'Edit Holiday' : 'Add New Holiday'"></h3>
                        <button @click="closeModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="isEdit ? '/admin/holidays/' + holidayId : '{{ route('admin.holidays.store') }}'" 
                          method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="modal_title" class="block text-sm font-medium text-slate-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="modal_title" 
                                   name="title" 
                                   x-model="formData.title"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g., Hari Raya Idul Fitri">
                        </div>

                        <!-- Date Field -->
                        <div class="mb-4">
                            <label for="modal_date" class="block text-sm font-medium text-slate-700 mb-1">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="modal_date" 
                                   name="holiday_date" 
                                   x-model="formData.holiday_date"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="modal_description" class="block text-sm font-medium text-slate-700 mb-1">
                                Description
                            </label>
                            <textarea id="modal_description" 
                                      name="description" 
                                      x-model="formData.description"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Optional description about this holiday"></textarea>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" 
                                    @click="closeModal()"
                                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition-all">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                <span x-text="isEdit ? 'Update' : 'Create'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function holidayModal() {
            return {
                isOpen: false,
                isEdit: false,
                holidayId: null,
                formData: {
                    title: '',
                    holiday_date: '',
                    description: ''
                },

                openModal(holidayId = null, title = '', date = '', description = '') {
                    if (holidayId) {
                        // Edit mode
                        this.isEdit = true;
                        this.holidayId = holidayId;
                        this.formData = {
                            title: title,
                            holiday_date: date,
                            description: description || ''
                        };
                    } else {
                        // Create mode
                        this.isEdit = false;
                        this.holidayId = null;
                        this.formData = {
                            title: '',
                            holiday_date: '',
                            description: ''
                        };
                    }
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                    // Reset form after animation
                    setTimeout(() => {
                        this.isEdit = false;
                        this.holidayId = null;
                        this.formData = {
                            title: '',
                            holiday_date: '',
                            description: ''
                        };
                    }, 300);
                }
            }
        }
    </script>
</x-app-layout>
