<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Require user to be authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show the profile view with current user data
    public function show()
    {
        $user = Auth::user();

        return view('profile', ['user' => $user]);
    }

    // Update profile data
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors('You must be logged in.');
        }

        // Validate input fields (add more as needed)
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // Add password update if needed, like:
            // 'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update($validated);

        // Redirect to settings page with success message
        return redirect()->route('settings')->with('success', 'Profile updated successfully!');
    }
}