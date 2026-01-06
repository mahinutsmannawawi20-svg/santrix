<?php

namespace App\Http\Controllers;

use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use Database\Seeders\DemoSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class DemoController extends Controller
{
    public function start(Request $request, $type = 'sekretaris')
    {
        // 1. Generate Unique ID for this Demo Session
        $demoId = strtolower(Str::random(6));
        $subdomain = 'demo-' . $demoId;
        
        // 2. Create Ephemeral Tenant
        try {
            DB::beginTransaction();

            $pesantren = Pesantren::create([
                'nama' => 'Pesantren Demo ' . strtoupper($demoId),
                'subdomain' => $subdomain,
                'domain' => $subdomain . '.' . config('app.url_base', 'santrix.my.id'),
                'status' => 'active', // Demo is always active
                'package' => 'demo',
                'expired_at' => now()->addHours(2), // Expire in 2 hours
                'trial_ends_at' => now()->addHours(2),
                'telepon' => '081234567890',
                'alamat' => 'Jl. Demo Virtual No. 1, Cloud City',
                'is_demo' => true, // Flag for cleanup
            ]);

            // 3. Create Admin User
            $user = User::create([
                'name' => 'Admin Demo',
                'email' => 'admin@' . $subdomain . '.test',
                'password' => Hash::make('password'), // Standard password
                'role' => 'admin',
                'pesantren_id' => $pesantren->id,
            ]);

            // 4. Create Dummy Subscription (so middleware doesn't block)
            Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => 'Demo Enterprise',
                'price' => 0,
                'started_at' => now(),
                'expired_at' => now()->addHours(2),
                'status' => 'active',
            ]);

            // 5. Seed Dummy Data
            // We call a specific Seeder passing the pesantren_id
            $seeder = new DemoSeeder();
            $seeder->run($pesantren);

            DB::commit();

            // 6. Auto Login & Redirect
            Auth::login($user);

            // Redirect based on requested type
            switch ($type) {
                case 'bendahara':
                    return redirect()->route('bendahara.dashboard')->with('success', 'Selamat datang di Mode Demo Bendahara! Data akan di-reset dalam 1 jam.');
                case 'pendidikan':
                    return redirect()->route('pendidikan.dashboard')->with('success', 'Selamat datang di Mode Demo Pendidikan! Data akan di-reset dalam 1 jam.');
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Mode Demo Admin! Data akan di-reset dalam 1 jam.');
                case 'sekretaris':
                default:
                    return redirect()->route('sekretaris.dashboard')->with('success', 'Selamat datang di Mode Demo Sekretaris! Data akan di-reset dalam 1 jam.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai demo: ' . $e->getMessage());
        }
    }
}
