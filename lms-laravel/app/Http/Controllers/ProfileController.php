<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    /**
     * Show the user's profile page (view only).
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        // You can add logic here to fetch profile image if needed
        return view('profile.show', compact('user'));
    }

    /**
     * Upload and update the user's profile image.
     */
    public function uploadProfileImage(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $user = $request->user();
        $file = $request->file('profile_image');
        $uploadDir = 'uploads/profile_images/';
        if (!is_dir(public_path($uploadDir))) {
            mkdir(public_path($uploadDir), 0755, true);
        }
        $ext = $file->getClientOriginalExtension();
        $newFileName = 'user_' . $user->id . '_' . time() . '.' . $ext;
        $file->move(public_path($uploadDir), $newFileName);

        // Update user profile_image in database
        $user->profile_image = $newFileName;
        $user->save();

        return back()->with('status', 'Profile image updated successfully.');
    }
}
