<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Pesantren;
use App\Models\Subscription;
use App\Services\Billing\InvoiceService;
use App\Services\Billing\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    protected $invoiceService;
    protected $subscriptionService;

    public function __construct(InvoiceService $invoiceService, SubscriptionService $subscriptionService)
    {
        $this->invoiceService = $invoiceService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Billing Overview.
     */
    public function index(Request $request)
    {
        $pesantren = $request->get('pesantren');
        if (!$pesantren && Auth::user()->pesantren_id) {
            $pesantren = Pesantren::find(Auth::user()->pesantren_id);
        }

        $subscription = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();

        $invoices = Invoice::where('pesantren_id', $pesantren->id)
            ->latest()
            ->paginate(10);

        return view('billing.index', compact('pesantren', 'subscription', 'invoices'));
    }

    /**
     * List of Subscription Plans.
     */
    public function plans(Request $request)
    {
        $pesantren = $request->get('pesantren');
        if (!$pesantren && Auth::user()->pesantren_id) {
            $pesantren = Pesantren::find(Auth::user()->pesantren_id);
        }

        return view('billing.plans', compact('pesantren'));
    }

    /**
     * Show Invoice Detail / Checkout.
     */
    public function show($id)
    {
        $invoice = Invoice::where('pesantren_id', Auth::user()->pesantren_id)
            ->findOrFail($id);

        return view('billing.show', compact('invoice'));
    }

    /**
     * Mock Payment.
     */
    public function pay(Request $request, $id)
    {
        $invoice = Invoice::where('pesantren_id', Auth::user()->pesantren_id)
            ->findOrFail($id);

        if ($invoice->status !== 'pending') {
            return back()->with('error', 'Tagihan ini tidak dapat dibayar.');
        }

        // Mock payment process
        $this->invoiceService->markAsPaid($invoice, Auth::user());

        return redirect()->route('admin.billing.index')->with('success', 'Pembayaran berhasil! Paket Anda telah diperbarui.');
    }

    /**
     * Create Invoice for a selected plan.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,advance',
        ]);

        $pesantren = Pesantren::find(Auth::user()->pesantren_id);
        $package = $request->package;
        $amount = ($package === 'advance') ? 3000000 : 1500000;
        
        // Calculate trial period or extension dates
        $sub = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();
            
        $startDate = ($sub && !$sub->expired_at->isPast()) ? $sub->expired_at : now();
        $endDate = $startDate->copy()->addMonths(6);

        $invoice = $this->invoiceService->createInvoice($pesantren, $package, $amount, $startDate, $endDate);

        return redirect()->route('admin.billing.show', $invoice->id);
    }
}
