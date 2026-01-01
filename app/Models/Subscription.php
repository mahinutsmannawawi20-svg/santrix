<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'pesantren_id',
        'package_name', // Keeping package_name as per existing, mapped to package in migration
        'price',
        'started_at',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('expired_at', '>=', now());
    }
}
