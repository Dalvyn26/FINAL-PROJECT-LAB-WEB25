<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <x-app-layout>
            <x-slot name="header">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-xl sm:text-2xl text-slate-900 leading-tight tracking-tight">
                            {{ __('Request Leave') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Submit a new leave request for approval</p>
                    </div>
                </div>
            </x-slot>

            <div class="py-8 bg-[#F8FAFC]" x-data="{
                leaveType: '{{ old('leave_type') }}',
                startDate: '',
                endDate: '',
                fileName: null,
                allWeekend: false,
                showWeekendWarning: false,
                isWeekendValid: true,
                flatpickrInstance: null,
                get minDate() {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Set to start of day to avoid timezone issues

                    if (this.leaveType === 'annual') {
                        // For annual leave, set minimum date to 3 days from now
                        const minDate = new Date(today);
                        minDate.setDate(minDate.getDate() + 3);
                        return minDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
                    } else {
                        // For sick leave, minimum date is today
                        return today.toISOString().split('T')[0]; // Format YYYY-MM-DD
                    }
                },
                validateDateRange() {
                    if (!this.startDate || !this.endDate) return;

                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);

                    if (start > end) return; // Don't validate if start date is after end date

                    let hasWorkDay = false;
                    let currentDate = new Date(start);

                    // Loop through each day in the range
                    while (currentDate <= end) {
                        const dayOfWeek = currentDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

                        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                            // It's a work day (Monday to Friday)
                            hasWorkDay = true;
                            break;
                        }

                        // Move to next day
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    // If no work day was found in the range, all days are weekend
                    this.allWeekend = !hasWorkDay;
                    this.showWeekendWarning = this.allWeekend && this.leaveType === 'annual';

                    // Enable/disable submit button based on validation
                    this.isWeekendValid = !this.allWeekend || this.leaveType !== 'annual';
                },
                updateFormState() {
                    this.validateDateRange();
                },
                resetForm() {
                    // Reset Alpine.js state
                    this.leaveType = '';
                    this.startDate = '';
                    this.endDate = '';
                    this.fileName = null;
                    this.allWeekend = false;
                    this.showWeekendWarning = false;
                    this.isWeekendValid = true;
                    
                    // Reset form using native form reset
                    const form = document.querySelector('form[method=\'POST\']');
                    if (form) {
                        form.reset();
                    }
                    
                    // Reset all form fields explicitly
                    // 1. Leave Type (select)
                    const leaveTypeSelect = document.getElementById('leave_type');
                    if (leaveTypeSelect) {
                        leaveTypeSelect.value = '';
                        // Trigger change event to update Alpine.js
                        leaveTypeSelect.dispatchEvent(new Event('change'));
                    }
                    
                    // 2. Hidden date inputs
                    const startDateHidden = document.getElementById('start_date_hidden');
                    const endDateHidden = document.getElementById('end_date_hidden');
                    if (startDateHidden) startDateHidden.value = '';
                    if (endDateHidden) endDateHidden.value = '';
                    
                    // 3. Date range picker display
                    const datePicker = document.getElementById('date_range_picker');
                    if (datePicker) {
                        datePicker.value = '';
                    }
                    
                    // 4. File input
                    const fileInput = document.getElementById('attachment');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    
                    // 5. Address During Leave (textarea)
                    const addressTextarea = document.getElementById('address_during_leave');
                    if (addressTextarea) {
                        addressTextarea.value = '';
                    }
                    
                    // 6. Emergency Contact (text input)
                    const emergencyInput = document.getElementById('emergency_contact');
                    if (emergencyInput) {
                        emergencyInput.value = '';
                    }
                    
                    // 7. Reason (textarea)
                    const reasonTextarea = document.getElementById('reason');
                    if (reasonTextarea) {
                        reasonTextarea.value = '';
                    }
                    
                    // Reset Flatpickr
                    if (this.flatpickrInstance) {
                        this.flatpickrInstance.clear();
                    }
                    
                    // Update form state after reset
                    this.updateFormState();
                },
                initFlatpickr() {
                    const self = this;
                    const dateInput = document.getElementById('date_range_picker');
                    
                    if (dateInput && typeof flatpickr !== 'undefined') {
                        // Destroy any existing instance
                        if (this.flatpickrInstance) {
                            this.flatpickrInstance.destroy();
                        }
                        
                        // Initialize flatpickr instance
                        this.flatpickrInstance = flatpickr(dateInput, {
                            mode: 'range',
                            dateFormat: 'd M Y',
                            minDate: this.minDate,
                            allowInput: true,
                            clickOpens: true,
                            disable: [
                                function(date) {
                                    // Disable past dates based on leave type
                                    const today = new Date();
                                    today.setHours(0, 0, 0, 0);

                                    let minAllowedDate = new Date(today);
                                    if (self.leaveType === 'annual') {
                                        // For annual leave, disable up to 3 days from today
                                        minAllowedDate.setDate(minAllowedDate.getDate() + 3);
                                    }

                                    return date < minAllowedDate;
                                }
                            ],
                            onChange: function(selectedDates, dateStr, instance) {
                                if (selectedDates.length === 2) {
                                    // Use Flatpickr's internal date formatter to avoid timezone conversion issues
                                    const startDate = instance.formatDate(selectedDates[0], 'Y-m-d');
                                    const endDate = instance.formatDate(selectedDates[1], 'Y-m-d');

                                    // Update Alpine.js reactive data
                                    self.startDate = startDate;
                                    self.endDate = endDate;

                                    // Update hidden input fields
                                    document.getElementById('start_date_hidden').value = startDate;
                                    document.getElementById('end_date_hidden').value = endDate;

                                    // Trigger validation
                                    self.updateFormState();
                                }
                            },
                            onClose: function(selectedDates, dateStr, instance) {
                                // Ensure validation runs when calendar is closed
                                if (selectedDates.length === 2) {
                                    self.updateFormState();
                                }
                            }
                        });
                    }
                }
            }" x-init="() => {
                $nextTick(() => {
                    // Initialize Flatpickr after DOM is ready
                    $data.initFlatpickr();
                    
                    // Add leaveType change listener to refresh Flatpickr settings
                    const leaveTypeSelect = document.getElementById('leave_type');
                    if (leaveTypeSelect) {
                        leaveTypeSelect.addEventListener('change', () => {
                            $data.leaveType = leaveTypeSelect.value;
                            
                            // Re-initialize Flatpickr to update disable rules
                            $data.initFlatpickr();
                            $data.updateFormState();
                        });
                    }
                });
            }">
                <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white/80 backdrop-blur-sm border border-[#E5E7EB] shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] rounded-[20px] p-4 sm:p-6 lg:p-8 transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 rounded-xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1]">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900">Apply for Leave</h3>
                        </div>
                        
                        <!-- Display user's leave quota -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-slate-700">
                                    Your remaining annual leave quota: <span class="font-bold text-slate-900">{{ Auth()->user()->leave_quota }} days</span>
                                </p>
                            </div>
                        </div>

                            @if($errors->any())
                                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-6">
                                    <label for="leave_type" class="block text-sm font-semibold text-slate-700 mb-2">Leave Type *</label>
                                    <select name="leave_type" id="leave_type" x-model="leaveType" x-on:change="updateFormState()" class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]">
                                        <option value="">Select Leave Type</option>
                                        @if($isEligible)
                                            <option value="annual">Annual Leave</option>
                                        @else
                                            <option value="annual" disabled class="text-gray-400">Annual Leave (Not Eligible: Work Period Under 1 Year)</option>
                                        @endif
                                        <option value="sick">Sick Leave</option>
                                    </select>
                                </div>

                                <!-- Hidden inputs to store the actual values for backend -->
                                <input type="hidden" name="start_date" id="start_date_hidden" required>
                                <input type="hidden" name="end_date" id="end_date_hidden" required>

                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Leave Dates *</label>
                                    <div class="relative">
                                        <input type="text" id="date_range_picker" readonly 
                                               placeholder="Select date range..." 
                                               class="w-full px-4 py-3 pl-12 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] cursor-pointer transition-all duration-200 hover:border-[#6B7280]">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-[#6B7280]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Weekend Warning Message -->
                                <template x-if="showWeekendWarning">
                                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-amber-700 text-sm leading-relaxed">
                                                Untuk cuti di hari Sabtu dan Minggu tidak membutuhkan pengajuan cuti, kecuali jika bersambung dengan hari kerja (misal: Jumat - Senin).
                                            </p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Holiday Info Message -->
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-blue-700 text-sm leading-relaxed">
                                            <strong>Catatan:</strong> Hari libur nasional (yang ada di sistem) tidak akan mengurangi kuota cuti Anda. Sistem akan otomatis melewati hari Sabtu, Minggu, dan hari libur nasional saat menghitung durasi cuti.
                                        </p>
                                    </div>
                                </div>

                                <div x-show="leaveType === 'sick'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                                    <div class="mb-4">
                                        <label for="attachment" class="block text-sm font-medium text-slate-600 mb-1">
                                            Medical Certificate *
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div x-ref="uploadArea" @click="$refs.fileInput.click()" class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-[#E5E7EB] border-dashed rounded-xl bg-[#F8FAFC] hover:bg-indigo-50/50 hover:border-[#4F46E5]/40 transition-all duration-300 cursor-pointer group">
                                            <input id="attachment" name="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png" x-ref="fileInput" @change="fileName = $refs.fileInput.files[0]?.name" class="hidden">

                                            <div class="space-y-1 text-center">
                                                <template x-if="!fileName">
                                                    <div>
                                                        <div class="flex text-sm text-slate-600 justify-center">
                                                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#4F46E5] to-[#6366F1] mb-2 group-hover:scale-110 transition-transform duration-300">
                                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <p class="mt-2 text-sm font-semibold text-slate-700">
                                                            <span class="text-[#4F46E5]">Upload medical certificate</span>
                                                            <span class="text-[#6B7280]"> or drag and drop</span>
                                                        </p>
                                                        <p class="text-xs text-[#6B7280] mt-1">
                                                            Format: PDF, JPG, JPEG, PNG. Max: 2MB
                                                        </p>
                                                    </div>
                                                </template>
                                                <template x-if="fileName">
                                                    <div>
                                                        <div class="flex text-sm text-emerald-600 justify-center">
                                                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <p class="mt-2 text-sm text-emerald-600 font-medium" x-text="fileName"></p>
                                                        <p class="text-xs text-slate-500">File selected successfully</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @error('attachment')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address During Leave -->
                                <div class="mb-6">
                                    <label for="address_during_leave" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Address During Leave *
                                    </label>
                                    <textarea name="address_during_leave" id="address_during_leave" rows="3" required class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280] resize-none">{{ old('address_during_leave') }}</textarea>
                                    @error('address_during_leave')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Emergency Contact -->
                                <div class="mb-6">
                                    <label for="emergency_contact" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Emergency Contact *
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" required class="w-full px-4 py-3 pl-12 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280]" placeholder="+62 812-3456-7890">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-[#6B7280]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('emergency_contact')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Leave Reason -->
                                <div class="mb-6">
                                    <label for="reason" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Reason *
                                    </label>
                                    <textarea name="reason" id="reason" rows="4" required class="w-full px-4 py-3 bg-white border border-[#E5E7EB] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] transition-all duration-200 hover:border-[#6B7280] resize-none">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-[#E5E7EB]">
                                    <a href="{{ route('leave-requests.index') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                                        Cancel
                                    </a>
                                    <button type="button" @click="resetForm()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-semibold py-3 px-6 sm:px-8 rounded-xl transition-all duration-300 hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </button>
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#4F46E5] to-[#6366F1] hover:from-[#4338CA] hover:to-[#4F46E5] text-white font-semibold py-3 px-6 sm:px-8 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-md" :disabled="!isWeekendValid" x-bind:class="!isWeekendValid ? 'opacity-50 cursor-not-allowed' : ''">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Submit Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flatpickr JS -->
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            
            <!-- Add x-cloak style -->
            <style>
                [x-cloak] { display: none !important; }
            </style>
        </x-app-layout>
    </body>
</html>