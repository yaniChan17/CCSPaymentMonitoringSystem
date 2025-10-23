<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
                <p class="mt-1 text-sm text-gray-600">Configure system-wide settings and preferences</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Settings Form -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- System Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    System Information
                </h2>
                <p class="text-sm text-gray-500 mb-4">Basic information about the system and institution</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">System Name</label>
                        <input type="text" 
                               name="system_name" 
                               value="{{ old('system_name', $settings['system_name']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('system_name') border-red-500 @enderror"
                               placeholder="CCS Payment Monitoring System">
                        @error('system_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">College Name</label>
                        <input type="text" 
                               name="college_name" 
                               value="{{ old('college_name', $settings['college_name']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('college_name') border-red-500 @enderror"
                               placeholder="College of Computer Studies">
                        @error('college_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year (Current)</label>
                        <input type="text" 
                               name="academic_year" 
                               value="{{ old('academic_year', $settings['academic_year']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('academic_year') border-red-500 @enderror"
                               placeholder="e.g., 2024-2025">
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select name="semester" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('semester') border-red-500 @enderror">
                            <option value="1st Semester" {{ old('semester', $settings['semester']) === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd Semester" {{ old('semester', $settings['semester']) === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="Summer" {{ old('semester', $settings['semester']) === 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Configuration -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Payment Configuration
                </h2>
                <p class="text-sm text-gray-500 mb-4">Configure default payment settings and deadlines</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Payment Amount (₱)</label>
                        <input type="number" 
                               name="default_payment_amount" 
                               value="{{ old('default_payment_amount', $settings['default_payment_amount']) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('default_payment_amount') border-red-500 @enderror"
                               placeholder="500.00">
                        <p class="mt-1 text-xs text-gray-500">Default amount for new payments</p>
                        @error('default_payment_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Deadline (Day of Month)</label>
                        <input type="number" 
                               name="payment_deadline_day" 
                               value="{{ old('payment_deadline_day', $settings['payment_deadline_day']) }}"
                               min="1"
                               max="31"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_deadline_day') border-red-500 @enderror"
                               placeholder="15">
                        <p class="mt-1 text-xs text-gray-500">Day of each month for payment deadline</p>
                        @error('payment_deadline_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Late Payment Grace Period (Days)</label>
                        <input type="number" 
                               name="late_fee_grace_period" 
                               value="{{ old('late_fee_grace_period', $settings['late_fee_grace_period']) }}"
                               min="0"
                               max="30"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('late_fee_grace_period') border-red-500 @enderror"
                               placeholder="3">
                        <p class="mt-1 text-xs text-gray-500">Days after deadline before late fee applies</p>
                        @error('late_fee_grace_period')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Late Fee Amount (₱)</label>
                        <input type="number" 
                               name="late_fee" 
                               value="{{ old('late_fee', $settings['late_fee']) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('late_fee') border-red-500 @enderror"
                               placeholder="50.00">
                        <p class="mt-1 text-xs text-gray-500">Fee charged for late payments</p>
                        @error('late_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notification Settings
                </h2>
                <p class="text-sm text-gray-500 mb-4">Configure email notifications and reminders</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="hidden" name="enable_email_notifications" value="0">
                            <input type="checkbox" 
                                   name="enable_email_notifications" 
                                   value="1"
                                   {{ old('enable_email_notifications', $settings['enable_email_notifications']) == '1' ? 'checked' : '' }}
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Enable Email Notifications</span>
                                <p class="text-xs text-gray-500">Send email notifications to students</p>
                            </span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reminder Days Before Deadline</label>
                        <input type="number" 
                               name="payment_reminder_days" 
                               value="{{ old('payment_reminder_days', $settings['payment_reminder_days']) }}"
                               min="1"
                               max="30"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_reminder_days') border-red-500 @enderror"
                               placeholder="7">
                        <p class="mt-1 text-xs text-gray-500">Days before deadline to send reminder</p>
                        @error('payment_reminder_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- System Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    System Preferences
                </h2>
                <p class="text-sm text-gray-500 mb-4">Customize system behavior and display options</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Records Per Page</label>
                        <select name="records_per_page" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('records_per_page') border-red-500 @enderror">
                            <option value="10" {{ old('records_per_page', $settings['records_per_page']) == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ old('records_per_page', $settings['records_per_page']) == '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ old('records_per_page', $settings['records_per_page']) == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ old('records_per_page', $settings['records_per_page']) == '100' ? 'selected' : '' }}>100</option>
                        </select>
                        @error('records_per_page')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select name="date_format" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('date_format') border-red-500 @enderror">
                            <option value="Y-m-d" {{ old('date_format', $settings['date_format']) === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="m/d/Y" {{ old('date_format', $settings['date_format']) === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                            <option value="d/m/Y" {{ old('date_format', $settings['date_format']) === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        </select>
                        @error('date_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                        <select name="export_format" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('export_format') border-red-500 @enderror">
                            <option value="xlsx" {{ old('export_format', $settings['export_format']) === 'xlsx' ? 'selected' : '' }}>Excel (XLSX)</option>
                            <option value="csv" {{ old('export_format', $settings['export_format']) === 'csv' ? 'selected' : '' }}>CSV</option>
                        </select>
                        @error('export_format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    System Maintenance
                </h2>
                <p class="text-sm text-gray-500 mb-4">Advanced system maintenance and data management settings</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="hidden" name="maintenance_mode" value="0">
                            <input type="checkbox" 
                                   name="maintenance_mode" 
                                   value="1"
                                   {{ old('maintenance_mode', $settings['maintenance_mode']) == '1' ? 'checked' : '' }}
                                   class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3">
                                <span class="text-sm font-medium text-gray-700">System Maintenance Mode</span>
                                <p class="text-xs text-gray-500">Put system in maintenance mode (only admins can access)</p>
                            </span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Retention Period (Months)</label>
                        <input type="number" 
                               name="data_retention_months" 
                               value="{{ old('data_retention_months', $settings['data_retention_months']) }}"
                               min="1"
                               max="120"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('data_retention_months') border-red-500 @enderror"
                               placeholder="24">
                        <p class="mt-1 text-xs text-gray-500">How long to keep old records (1-120 months)</p>
                        @error('data_retention_months')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Information
                </h2>
                <p class="text-sm text-gray-500 mb-4">Contact details for system support and inquiries</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" 
                               name="contact_email" 
                               value="{{ old('contact_email', $settings['contact_email']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('contact_email') border-red-500 @enderror"
                               placeholder="css@example.com">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="text" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', $settings['contact_phone']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('contact_phone') border-red-500 @enderror"
                               placeholder="+63 123 456 7890">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-sidebar-layout>
