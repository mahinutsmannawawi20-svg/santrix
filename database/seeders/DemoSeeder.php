<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesantren;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\TagihanSyahriah;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(Pesantren $pesantren)
    {
        // 1. Setup Tahun Ajaran
        $tahunAjaran = TahunAjaran::create([
            'pesantren_id' => $pesantren->id,
            'tahun' => '2025/2026',
            'semester' => 'ganjil',
            'is_active' => true,
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
        ]);

        // 2. Setup Kelas
        $kelas1 = Kelas::create(['pesantren_id' => $pesantren->id, 'nama_kelas' => '1 A', 'tingkat' => '1']);
        $kelas2 = Kelas::create(['pesantren_id' => $pesantren->id, 'nama_kelas' => '2 B', 'tingkat' => '2']);

        // 3. Setup Santri (5-10 data)
        $santris = [];
        $names = ['Ahmad Fulan', 'Siti Aminah', 'Budi Santoso', 'Dewi Sartika', 'Rizky Ramadhan'];
        
        foreach ($names as $name) {
            $santris[] = Santri::create([
                'pesantren_id' => $pesantren->id,
                'nis' => rand(10000, 99999),
                'nama' => $name,
                'jenis_kelamin' => (str_contains($name, 'Siti') || str_contains($name, 'Dewi')) ? 'P' : 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-01-01',
                'alamat' => 'Jl. Contoh No. 10',
                'nama_wali' => 'Bapak ' . explode(' ', $name)[0],
                'no_hp_wali' => '0812' . rand(10000000, 99999999),
                'status' => 'aktif',
                'kelas_id' => $kelas1->id,
                'tanggal_masuk' => now()->subMonths(6),
            ]);
        }

        // 4. Setup Finance Data (Bendahara)
        // Saldo Awal (Pemasukan)
        Pemasukan::create([
            'pesantren_id' => $pesantren->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'kategori' => 'Donasi',
            'jumlah' => 50000000,
            'keterangan' => 'Saldo Awal Demo',
            'tanggal' => now()->subDays(5),
            'metode_pembayaran' => 'transfer',
        ]);

        // Pengeluaran Dummy
        Pengeluaran::create([
            'pesantren_id' => $pesantren->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'kategori' => 'Operasional',
            'jumlah' => 1500000,
            'keterangan' => 'Beli ATK Kantor',
            'tanggal' => now()->subDays(2),
        ]);

        // Tagihan SPP to a Santri
        if (!empty($santris)) {
            TagihanSyahriah::create([
                'pesantren_id' => $pesantren->id,
                'santri_id' => $santris[0]->id,
                'bulan' => Carbon::now()->month,
                'tahun' => Carbon::now()->year,
                'jumlah' => 150000,
                'status' => 'lunas', // One paid
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]);
            
            TagihanSyahriah::create([
                'pesantren_id' => $pesantren->id,
                'santri_id' => $santris[1]->id,
                'bulan' => Carbon::now()->month,
                'tahun' => Carbon::now()->year,
                'jumlah' => 150000,
                'status' => 'belum_lunas', // One unpaid
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]);
        }
    }
}
