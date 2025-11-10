<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit-custom', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Validate profile fields
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'student_id' => ['nullable', 'string', 'max:50'],
            'course' => ['nullable', 'string', 'max:100'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'block' => ['nullable', 'string', 'max:50'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:20'],
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        // Update user info
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'student_id' => $validated['student_id'] ?? $user->student_id,
            'course' => $validated['course'] ?? $user->course,
            'year_level' => $validated['year_level'] ?? $user->year_level,
            'block' => $validated['block'] ?? $user->block,
            'contact_number' => $validated['contact_number'] ?? $user->contact_number,
            'father_name' => $validated['father_name'] ?? $user->father_name,
            'mother_name' => $validated['mother_name'] ?? $user->mother_name,
            'address' => $validated['address'] ?? $user->address,
            'photo' => $validated['photo'] ?? $user->photo,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update student record if exists
        if ($user->student) {
            $studentData = [];
            
            // Admin can update student_id and block
            if (Auth::user()->role === 'admin') {
                if (isset($validated['student_id'])) {
                    $studentData['student_id'] = $validated['student_id'];
                }
                if (isset($validated['block'])) {
                    $studentData['block'] = $validated['block'];
                }
            }
            
            // All roles can update these fields
            if (isset($validated['contact_number'])) {
                $studentData['contact_number'] = $validated['contact_number'];
            }
            if (isset($validated['guardian_name'])) {
                $studentData['guardian_name'] = $validated['guardian_name'];
            }
            if (isset($validated['guardian_contact'])) {
                $studentData['guardian_contact'] = $validated['guardian_contact'];
            }
            if (isset($validated['address'])) {
                $studentData['address'] = $validated['address'];
            }
            
            if (!empty($studentData)) {
                $user->student->update($studentData);
            }
        }

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->student && $user->student->profile_photo) {
            Storage::disk('public')->delete('profile_photos/' . $user->student->profile_photo);
        }

        // Store new photo
        $file = $request->file('profile_photo');
        $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('profile_photos', $filename, 'public');

        // Update student record
        if ($user->student) {
            $user->student->update([
                'profile_photo' => $filename,
            ]);
        }

        return Redirect::route('profile.edit')->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->student && $user->student->profile_photo) {
            // Delete file from storage
            Storage::disk('public')->delete('profile_photos/' . $user->student->profile_photo);
            
            // Remove from database
            $user->student->update([
                'profile_photo' => null,
            ]);
        }

        return Redirect::route('profile.edit')->with('success', 'Profile photo removed successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
