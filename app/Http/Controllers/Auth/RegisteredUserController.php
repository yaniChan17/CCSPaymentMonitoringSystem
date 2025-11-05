<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Split name into first and last name
        $nameParts = explode(' ', $request->name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Generate a temporary student ID (can be updated by admin later)
        $studentIdNumber = 'TEMP-' . strtoupper(substr(uniqid(), -6));

        // Create student profile first
        $student = Student::create([
            'student_id' => $studentIdNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $request->email,
            'contact_number' => '', // Will be filled later
            'course' => 'BSIT', // Default to BSIT for CCS
            'year_level' => '1st Year', // Default to first year
            'block' => null, // Will be assigned by admin
            'total_fees' => 0.00,
            'balance' => 0.00,
            'status' => 'pending', // Pending until admin approves
        ]);

        // Create user account linked to student profile
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
