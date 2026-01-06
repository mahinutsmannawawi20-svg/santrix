<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKelas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kelas';

    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester',
        'catatan',
        'status', // promoted, retained, graduated
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
