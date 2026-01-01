<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Services\Billing\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisterTenantController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function showRegistrationForm(Request $request)
    {
        // Validate package parameter
        $package = $request->query('package');
        
        if (!in_array($package, ['basic', 'advance'])) {
            return redirect()->route('landing')->with('error', 'Silakan pilih paket terlebih dahulu.');
        }

        $packageData = config('subscription.plans');
        $selectedPlan = collect($packageData)->firstWhere('id', $package);

        return view('auth.register-tenant', compact('package', 'selectedPlan'));
    }

    public function register(Request $request)
    {
        // Validate package
        $package = $request->input('package');
        if (!in_array($package, ['basic', 'advance'])) {
            return back()->with('error', 'Paket tidak valid.')->withInput();
        }

        $validationRules = [
            'package' => 'required|in:basic,advance',
            'nama_pesantren' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9-]+$/',
                'unique:pesantrens,subdomain',
                'not_in:www,admin,panel,dashboard,api,owner'
            ],
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ];

        // Add bank account validation for advance package
        if ($package === 'advance') {
            $validationRules['bank_name'] = 'required|string|max:100';
            $validationRules['bank_account_number'] = 'required|string|max:50';
            $validationRules['bank_account_name'] = 'required|string|max:255';
        }

        $request->validate($validationRules, [
            'subdomain.regex' => 'Subdomain hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
            'subdomain.unique' => 'Subdomain ini sudah digunakan, silakan pilih yang lain.',
            'subdomain.not_in' => 'Subdomain ini tidak diizinkan.'
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Pesantren
            $trialEndsAt = now()->addDays(7);
            $deleteAt = now()->addDays(11); // 7 days trial + 4 days grace

            $pesantrenData = [
                'nama' => $request->nama_pesantren,
                'subdomain' => $request->subdomain,
                'domain' => $request->subdomain . '.' . config('app.url_base', 'santrix.my.id'),
                'status' => 'trial',
                'package' => $package,
                'expired_at' => $trialEndsAt,
                'trial_ends_at' => $trialEndsAt,
                'delete_at' => $deleteAt,
                'telepon' => $request->phone,
            ];

            // Add bank details if advance
            if ($package === 'advance') {
                $pesantrenData['bank_name'] = $request->bank_name;
                $pesantrenData['bank_account_number'] = $request->bank_account_number;
                $pesantrenData['bank_account_name'] = $request->bank_account_name;
            }

            $pesantren = Pesantren::create($pesantrenData);

            // 2. Create Owner User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
                'pesantren_id' => $pesantren->id,
            ]);

            // 3. Create Trial Subscription Record (7 days)
            $subscription = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => ucfirst($package) . ' (Trial)',
                'price' => 0,
                'started_at' => now(),
                'expired_at' => $trialEndsAt,
                'status' => 'trial',
            ]);

            // 4. Create Pending Invoice (due after trial)
            $amount = ($package === 'advance') ? 3000000 : 1500000;
            $invoice = $this->invoiceService->createInvoice(
                $pesantren,
                $package,
                $amount,
                $trialEndsAt,
                $trialEndsAt->copy()->addMonths(6)
            );

            DB::commit();

            // 5. Auto Login
            Auth::login($user);

            // 6. Redirect to Dashboard with trial info
            return redirect()->route('owner.dashboard')->with('success', 'Selamat datang! Masa trial 7 hari Anda dimulai sekarang. Jangan lupa upgrade sebelum trial berakhir!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage())->withInput();
        }
    }
}
