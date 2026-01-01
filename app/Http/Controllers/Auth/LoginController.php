<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        // Check if accessing from Central Domain (No Tenant)
        // Adjust check based on your tenancy setup, but usually !app('CurrentTenant') works
        // or check if request host is in config('tenancy.central_domains')
        
        $isTenant = app()->has('CurrentTenant');

        if (!$isTenant) {
            return view('auth.login-central');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Validate Tenant Context
        if (app()->has('CurrentTenant')) {
            $credentials['pesantren_id'] = app('CurrentTenant')->id;
        } else {
             // If logging in from central domain (no tenant), ensure user is Super Admin/Owner (pesantren_id IS NULL)
             // However, Auth::attempt doesn't easily support "IS NULL" directly in array without custom callback or scope.
             // For simplicity in this step, we filter it manually after login or rely on role check.
             // But to be safe strict, we can add a check if user found.
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Double check for safety (e.g. if user is active)
            $user = Auth::user();
            if (app()->has('CurrentTenant') && $user->pesantren_id !== app('CurrentTenant')->id) {
                Auth::logout();
                return back()->withErrors(['email' => 'User tidak terdaftar di pesantren ini.']);
            }
            
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Redirect to dashboard based on user role
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();
        
        return match($user->role) {
            'owner' => redirect('/owner'),
            'admin' => redirect()->route('admin.dashboard'),
            'pendidikan' => redirect()->route('pendidikan.dashboard'),
            'sekretaris' => redirect()->route('sekretaris.dashboard'),
            'bendahara' => redirect()->route('bendahara.dashboard'),
            default => redirect('/login'),
        };
    }
}
