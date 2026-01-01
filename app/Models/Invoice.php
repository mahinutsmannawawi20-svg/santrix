<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'pesantren_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'status',
        'payment_method',
        'payment_details',
        'paid_at',
        'paid_by_user_id',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function pesantren()
    {
         return $this->belongsTo(Pesantren::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }
    
    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
