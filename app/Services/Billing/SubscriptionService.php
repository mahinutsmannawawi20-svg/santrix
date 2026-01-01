<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Pesantren;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Handle the activation or extension of a subscription after an invoice is paid.
     */
    public function handlePaidInvoice(Invoice $invoice): Subscription
    {
        $pesantren = $invoice->pesantren;
        
        // Find current or latest subscription
        $subscription = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();

        $now = now();
        $isUpgrade = false;
        
        // Determine package from amount or metadata (for prompt #3 simplicity, we can use amount or assumes advance if high)
        // Basic: 1.500.000, Advance: 3.000.000
        $package = ($invoice->amount >= 3000000) ? 'advance' : 'basic';

        if (!$subscription) {
            // Create new subscription if none exists
            $subscription = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => $package,
                'price' => $invoice->amount,
                'started_at' => $now,
                'expired_at' => $now->copy()->addMonths(6),
                'status' => 'active',
            ]);
        } else {
            // Logic for existing subscription
            $isUpgrade = ($package === 'advance' && $subscription->package_name === 'basic');
            
            if ($subscription->expired_at->isPast()) {
                // If already expired, start fresh from today
                $subscription->update([
                    'package_name' => $package,
                    'price' => $invoice->amount,
                    'started_at' => $now,
                    'expired_at' => $now->copy()->addMonths(6),
                    'status' => 'active',
                ]);
            } else {
                // If still active
                if ($isUpgrade) {
                    // Upgrade: Update package and extend from today (or max(expired_at, now))
                    // Per user request: upgrade = langsung ganti package, masa aktif tetap:
                    // expired_at = max(expired_at, today) + 6 bulan
                    $newExpiry = Carbon::max($subscription->expired_at, $now)->addMonths(6);
                    $subscription->update([
                        'package_name' => $package,
                        'expired_at' => $newExpiry,
                        'status' => 'active',
                    ]);
                } else {
                    // Extend: Same package, just add 6 months to current expiry
                    $subscription->update([
                        'expired_at' => $subscription->expired_at->addMonths(6),
                        'status' => 'active',
                    ]);
                }
            }
        }

        // Link invoice to subscription
        $invoice->update(['subscription_id' => $subscription->id]);

        // Sync to Pesantren table (Denormalized Cache)
        $this->syncToPesantren($pesantren, $subscription);

        return $subscription;
    }

    /**
     * Sync subscription status to the Pesantren model for fast access.
     */
    public function syncToPesantren(Pesantren $pesantren, Subscription $subscription): void
    {
        $pesantren->update([
            'package' => $subscription->package_name,
            'status' => $subscription->status,
            'expired_at' => $subscription->expired_at,
        ]);
    }

    /**
     * Real-time check if subscription is expired for middleware gating.
     */
    public function isExpired(Pesantren $pesantren): bool
    {
        return empty($pesantren->expired_at) || Carbon::parse($pesantren->expired_at)->isPast();
    }
}
