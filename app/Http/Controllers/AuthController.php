<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Add this import

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();        

            // Clear previous sessions for security
            Auth::logoutOtherDevices($request->password);

            $user = Auth::user(); 

            // Add log entry for successful login
            if ($user->role === 'admin') { 
                Log::info('Admin "' . $request->username . '" logged in successfully.');
            } else {
                Log::info('User "' . $request->username . '" logged in successfully.');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Successfully logged in!');
        }

        // Add log entry for failed login attempt
        Log::warning('Failed login attempt for username "' . $request->username . '".');

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
{
    $user = Auth::user();
    $username = $user ? $user->username : 'Unknown User';

    Auth::logout();

    // Clear the session and regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Log user logout activity
    Log::info(($user && $user->role === 'admin' ? 'Admin' : 'User') . ' "' . $username . '" logged out.');

    return redirect()->route('login')
        ->with('success', 'Successfully logged out!');
}

}