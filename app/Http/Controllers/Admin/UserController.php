<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('student'); // Include all users (including current admin)

        // Search filter (name or email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query()); // Maintain query params in pagination

        // Calculate accurate stats
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'treasurers' => User::where('role', 'treasurer')->count(),
            'students' => User::where('role', 'student')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,treasurer,student'],
            // Student-specific fields
            'student_id' => ['required_if:role,student', 'nullable', 'string', 'unique:students,student_id'],
            'course' => ['required_if:role,student', 'nullable', 'string'],
            'year_level' => ['required_if:role,student', 'nullable', 'string'],
            'block' => ['required_if:role,student', 'nullable', 'string', 'max:10'],
            // Admin/Treasurer credentials
            'government_id_type' => ['required_if:role,admin,treasurer', 'nullable', 'in:driver_license,passport,sss_id,umid,philhealth_id'],
            'government_id_number' => ['required_if:role,admin,treasurer', 'nullable', 'string'],
            'government_id_file' => ['required_if:role,admin,treasurer', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Handle government ID file upload
        if (in_array($validated['role'], ['admin', 'treasurer']) && $request->hasFile('government_id_file')) {
            $validated['government_id_file'] = $request->file('government_id_file')->store('government_ids', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'government_id_type' => $validated['government_id_type'] ?? null,
            'government_id_number' => $validated['government_id_number'] ?? null,
            'government_id_file' => $validated['government_id_file'] ?? null,
        ]);

        // Create student record if role is student
        if ($validated['role'] === 'student') {
            // Split name into first and last name
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Convert year_level number to formatted string
            $yearLevelMap = ['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year'];
            $yearLevelFormatted = $yearLevelMap[$validated['year_level']] ?? $validated['year_level'];

            $student = Student::create([
                'student_id' => $validated['student_id'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'course' => $validated['course'] ?? 'BSIT',
                'year_level' => $yearLevelFormatted,
                'block' => $validated['block'] ?? null,
                'status' => 'active',
            ]);

            // Link the student to the user
            $user->update(['student_id' => $student->id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('student.payments');
        
        $stats = null;
        if ($user->role === 'student' && $user->student) {
            $stats = [
                'total_payments' => $user->student->payments->count(),
                'total_paid' => $user->student->payments->where('status', 'paid')->sum('amount'),
                'pending_amount' => $user->student->payments->where('status', 'pending')->sum('amount'),
            ];
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Prevent editing own account through this interface
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.edit')
                ->with('info', 'Please use the profile page to edit your own account.');
        }

        $user->load('student');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Prevent updating own account through this interface
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot update your own account through this interface.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,treasurer,student'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            // Student-specific fields
            'student_id' => [
                'required_if:role,student',
                'nullable',
                'string',
                Rule::unique('students', 'student_id')->ignore($user->student?->id)
            ],
            'course' => ['required_if:role,student', 'nullable', 'string'],
            'year_level' => ['required_if:role,student', 'nullable', 'string'],
            'block' => ['required_if:role,student', 'nullable', 'string', 'max:10'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Handle student record
        if ($validated['role'] === 'student') {
            // Split name into first and last name
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Convert year_level number to formatted string
            $yearLevelMap = ['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year'];
            $yearLevelFormatted = $yearLevelMap[$validated['year_level']] ?? $validated['year_level'];

            if ($user->student) {
                // Update existing student record
                $user->student->update([
                    'student_id' => $validated['student_id'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'course' => $validated['course'] ?? 'BSIT',
                    'year_level' => $yearLevelFormatted,
                    'block' => $validated['block'] ?? null,
                    'status' => $validated['status'] ?? $user->student->status,
                ]);
            } else {
                // Create new student record
                $student = Student::create([
                    'student_id' => $validated['student_id'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                    'course' => $validated['course'] ?? 'BSIT',
                    'year_level' => $yearLevelFormatted,
                    'block' => $validated['block'] ?? null,
                    'status' => $validated['status'] ?? 'active',
                ]);

                // Link the student to the user
                $user->update(['student_id' => $student->id]);
            }
        } else {
            // If role changed from student to something else, optionally deactivate student record
            if ($user->student) {
                $user->student->update(['status' => 'inactive']);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account.');
        }

        // Prevent deleting if user is a student with payments
        if ($user->role === 'student' && $user->student && $user->student->payments()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete student with existing payment records. Please archive instead.');
        }

        $userName = $user->name;
        
        // Delete associated student record if exists
        if ($user->student) {
            $user->student->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' deleted successfully!");
    }
}
