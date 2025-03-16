<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Validate the input data (username and password)
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user with username and password
        $credentials = $request->only('username', 'password');

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Store username in session
            session(['username' => $user->username]);

            // Check if the logged-in user is a superadmin
            if ($user->role === 'superadmin') {
                return redirect()->route('dashboard'); // Redirect to the dashboard
            }

            return redirect()->intended('/dashboard'); // Redirect for non-superadmin users
        }

        // If authentication fails, return with an error message
        return back()->withErrors(['login' => 'Invalid credentials.']);
    }

    /**
     * Log out the user.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out

        // Redirect to the home page after logging out
        return redirect('/');
    }
}


