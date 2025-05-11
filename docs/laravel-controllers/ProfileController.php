<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        
        $validated = $request->validated();
        
        // Check if email is being changed
        if ($user->email !== $validated['email']) {
            // Check if email is already taken
            if (\App\Models\User::where('email', $validated['email'])->exists()) {
                return back()->withErrors(['email' => 'The email address is already taken.']);
            }
        }
        
        // Check if username is being changed
        if ($user->user_name !== $validated['user_name']) {
            // Check if username is already taken
            if (\App\Models\User::where('user_name', $validated['user_name'])->exists()) {
                return back()->withErrors(['user_name' => 'The username is already taken.']);
            }
        }
        
        // Update user profile
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
        ]);
        
        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        
        $validated = $request->validated();
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return back()->with('success', 'Password updated successfully.');
    }
}