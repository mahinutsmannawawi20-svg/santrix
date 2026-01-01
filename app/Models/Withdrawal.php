<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesantren_id',
        'amount',
        'status', // pending, approved, rejected
        'bank_name',
        'account_number',
        'account_name',
        'proof_path',
        'admin_note'
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
