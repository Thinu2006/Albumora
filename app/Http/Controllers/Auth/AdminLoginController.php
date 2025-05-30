<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('auth.admin-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->user_type === 'admin') {
                $token = $user->createToken('admin-token')->plainTextToken;
                
                // Store in session for Blade template
                session(['auth_token' => $token]);
                
                return redirect()->route('admin.dashboard')
                    ->with('token', $token)
                    ->withCookie(cookie('auth_token', $token, 60*24*7));
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Unauthorized login attempt.']);
        }   
        
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function destroy(Request $request)
    {
        // Delete the current access token
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
        // Clear the session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear the token from cookies
        $cookie = cookie()->forget('auth_token');
        
        // Return with a script to clear localStorage
        return response()
            ->view('auth.admin-login')
            ->withCookie($cookie)
            ->withHeaders([
                'Content-Security-Policy' => "script-src 'self' 'unsafe-inline'",
            ])
            ->setContent("<script>localStorage.removeItem('auth_token'); window.location.href = '/admin/login';</script>");
    }
}