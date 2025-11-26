<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Leave Request') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-6">Edit Leave Request</h3>
                    
                    <!-- Display user's leave quota -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-md">
                        <p class="text-gray-700 dark:text-gray-300">
                            Your remaining annual leave quota: <span class="font-semibold">{{ Auth()->user()->leave_quota + $leaveRequest->total_days }} days</span>
                            <br>
                            <small>(This shows your quota if this request is canceled)</small>
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave-requests.update', $leaveRequest) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="leave_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type *</label>
                            <select name="leave_type" id="leave_type" x-model="leaveType" disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white bg-gray-100 dark:bg-gray-600">
                                <option value="annual" {{ $leaveRequest->leave_type === 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="sick" {{ $leaveRequest->leave_type === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Leave type cannot be changed after creation</p>
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date *</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $leaveRequest->start_date) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date *</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $leaveRequest->end_date) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        @if($leaveRequest->leave_type === 'sick')
                            <div class="mb-4">
                                <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medical Certificate</label>
                                @if($leaveRequest->attachment_path)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                        Current file: <a href="{{ Storage::url($leaveRequest->attachment_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View File</a>
                                    </p>
                                @endif
                                <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Max file size: 2MB. Allowed formats: PDF, JPG, JPEG, PNG</p>
                            </div>
                        @endif

                        <!-- Address During Leave -->
                        <div class="mb-4">
                            <label for="address_during_leave" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address During Leave *</label>
                            <input type="text" name="address_during_leave" id="address_during_leave" value="{{ old('address_during_leave', $leaveRequest->address_during_leave) }}" required disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white bg-gray-100 dark:bg-gray-600">
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-4">
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Emergency Contact *</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $leaveRequest->emergency_contact) }}" required disabled class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white bg-gray-100 dark:bg-gray-600" placeholder="Phone number">
                        </div>

                        <!-- Leave Reason -->
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason *</label>
                            <textarea name="reason" id="reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">{{ old('reason', $leaveRequest->reason) }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6">
                            <a href="{{ route('leave-requests.index') }}" class="w-full sm:w-auto text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2.5 px-4 sm:px-6 rounded text-sm sm:text-base">
                                Cancel
                            </a>
                            <button type="submit" class="w-full sm:w-auto text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2.5 px-4 sm:px-6 rounded text-sm sm:text-base">
                                Update Request
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
                leaveType: '{{ $leaveRequest->leave_type }}',
                
                init() {
                    // Set initial value
                    const selectElement = document.getElementById('leave_type');
                    if (selectElement) {
                        this.leaveType = selectElement.value;
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