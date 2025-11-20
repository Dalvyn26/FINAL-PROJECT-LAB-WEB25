<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Request Leave') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 transition-all">
                <div class="text-slate-700">
                    <h3 class="text-lg font-semibold text-slate-800 mb-6">Apply for Leave</h3>

                    <!-- Display user's leave quota -->
                    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <p class="text-slate-700">
                            Your remaining annual leave quota: <span class="font-bold text-slate-800">{{ Auth()->user()->leave_quota }} days</span>
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="leave_type" class="block text-sm font-medium text-slate-600 mb-1">Leave Type *</label>
                            <select name="leave_type" id="leave_type" x-model="leaveType" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Leave Type</option>
                                <option value="annual">Annual Leave</option>
                                <option value="sick">Sick Leave</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-slate-600 mb-1">Start Date *</label>
                            <input type="date" name="start_date" id="start_date" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-slate-600 mb-1">End Date *</label>
                            <input type="date" name="end_date" id="end_date" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-4" x-show="leaveType === 'sick'" x-cloak>
                            <label for="attachment" class="block text-sm font-medium text-slate-600 mb-1">Medical Certificate *</label>
                            <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-slate-500">Max file size: 2MB. Allowed formats: PDF, JPG, JPEG, PNG</p>
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-slate-600 mb-1">Reason *</label>
                            <textarea name="reason" id="reason" rows="4" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('reason') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('leave-requests.index') }}" class="mr-4 bg-slate-500 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all hover:-translate-y-0.5">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('leaveForm', () => ({
                leaveType: '',

                init() {
                    // Set initial value if editing
                    const selectElement = document.getElementById('leave_type');
                    if (selectElement) {
                        this.leaveType = selectElement.value;
                        selectElement.addEventListener('change', (e) => {
                            this.leaveType = e.target.value;
                        });
                    }
                }
            }));
        });
    </script>

    <!-- Add x-cloak style -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>