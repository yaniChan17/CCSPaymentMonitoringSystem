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
            // Academic Settings
            'academic_year' => Setting::get('academic_year', '2024-2025'),
            'semester' => Setting::get('semester', '1st Semester'),
            
            // Payment Settings
            'default_payment_amount' => Setting::get('default_payment_amount', '500.00'),
            'late_fee' => Setting::get('late_fee', '50.00'),
            'payment_deadline_day' => Setting::get('payment_deadline_day', '15'),
            
            // System Settings
            'system_name' => Setting::get('system_name', 'CSS Payment Monitoring System'),
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
            'academic_year' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'default_payment_amount' => ['required', 'numeric', 'min:0'],
            'late_fee' => ['required', 'numeric', 'min:0'],
            'payment_deadline_day' => ['required', 'integer', 'min:1', 'max:31'],
            'system_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['default_payment_amount', 'late_fee', 'payment_deadline_day']) 
                ? 'number' 
                : 'string';
            
            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
