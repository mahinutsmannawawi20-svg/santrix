<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisterTenantController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-tenant');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pesantren' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9-]+$/', // Only lowercase alphanumeric and dashes
                'unique:pesantrens,subdomain',
                'not_in:www,admin,panel,dashboard,api' // Reserved subdomains
            ],
            'name' => 'required|string|max:255', // Owner Name
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ], [
            'subdomain.regex' => 'Subdomain hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
            'subdomain.unique' => 'Subdomain ini sudah digunakan, silakan pilih yang lain.',
            'subdomain.not_in' => 'Subdomain ini tidak diizinkan.'
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Pesantren
            $pesantren = Pesantren::create([
                'nama' => $request->nama_pesantren,
                'subdomain' => $request->subdomain,
                'domain' => $request->subdomain . '.' . config('app.url_base', 'santrix.id'), // Fallback if env not set
                'status' => 'active', // Trial is active
                'package' => 'trial',
                'expired_at' => now()->addDays(14), // 14 Days Trial
                'telepon' => $request->phone,
            ]);

            // 2. Create Owner User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
                'pesantren_id' => $pesantren->id,
            ]);

            // 3. Create Trial Subscription Record
            Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => 'Trial 14 Hari',
                'price' => 0,
                'started_at' => now(),
                'expired_at' => now()->addDays(14),
                'status' => 'active',
            ]);

            DB::commit();

            // 4. Auto Login
            Auth::login($user);

            // 5. Redirect to Dashboard (Tenant URL logic needed if using subdomains locally)
            // For now, redirect to /owner which handles the logic
            return redirect()->route('owner.dashboard')->with('success', 'Selamat datang! Akun pesantren Anda siap digunakan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage())->withInput();
        }
    }
}
