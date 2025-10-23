<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            // System Information
            'system_name' => Setting::get('system_name', 'CCS Payment Monitoring System'),
            'college_name' => Setting::get('college_name', 'College of Computer Studies'),
            'academic_year' => Setting::get('academic_year', '2024-2025'),
            'semester' => Setting::get('semester', '1st Semester'),

            // Payment Configuration
            'default_payment_amount' => Setting::get('default_payment_amount', '500.00'),
            'payment_deadline_day' => Setting::get('payment_deadline_day', '15'),
            'late_fee_grace_period' => Setting::get('late_fee_grace_period', '3'),
            'late_fee' => Setting::get('late_fee', '50.00'),

            // Notification Settings
            'enable_email_notifications' => Setting::get('enable_email_notifications', '1'),
            'payment_reminder_days' => Setting::get('payment_reminder_days', '7'),

            // System Preferences
            'records_per_page' => Setting::get('records_per_page', '20'),
            'date_format' => Setting::get('date_format', 'Y-m-d'),
            'export_format' => Setting::get('export_format', 'xlsx'),

            // System Maintenance
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'data_retention_months' => Setting::get('data_retention_months', '24'),

            // Contact Information
            'contact_email' => Setting::get('contact_email', 'css@example.com'),
            'contact_phone' => Setting::get('contact_phone', '+63 123 456 7890'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // System Information
            'system_name' => ['required', 'string', 'max:255'],
            'college_name' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],

            // Payment Configuration
            'default_payment_amount' => ['required', 'numeric', 'min:0'],
            'payment_deadline_day' => ['required', 'integer', 'min:1', 'max:31'],
            'late_fee_grace_period' => ['required', 'integer', 'min:0', 'max:30'],
            'late_fee' => ['required', 'numeric', 'min:0'],

            // Notification Settings
            'enable_email_notifications' => ['required', 'boolean'],
            'payment_reminder_days' => ['required', 'integer', 'min:1', 'max:30'],

            // System Preferences
            'records_per_page' => ['required', 'integer', 'min:10', 'max:100'],
            'date_format' => ['required', 'string', 'in:Y-m-d,m/d/Y,d/m/Y'],
            'export_format' => ['required', 'string', 'in:xlsx,csv'],

            // System Maintenance
            'maintenance_mode' => ['required', 'boolean'],
            'data_retention_months' => ['required', 'integer', 'min:1', 'max:120'],

            // Contact Information
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            $type = 'string';
            if (in_array($key, ['default_payment_amount', 'late_fee'])) {
                $type = 'number';
            } elseif (in_array($key, ['payment_deadline_day', 'late_fee_grace_period', 'payment_reminder_days', 'records_per_page', 'data_retention_months'])) {
                $type = 'number';
            } elseif (in_array($key, ['enable_email_notifications', 'maintenance_mode'])) {
                $type = 'boolean';
            }

            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
