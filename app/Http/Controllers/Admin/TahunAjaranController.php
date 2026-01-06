<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('nama', 'desc')->get();
        return view('admin.settings.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        if ($request->is_active) {
            // Deactivate all others
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            // Clear Cache
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_id');
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_name');
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran');
        }

        TahunAjaran::create($request->all());

        return redirect()->route('admin.pengaturan.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama,' . $id,
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        if ($request->is_active && !$tahun->is_active) {
            // Deactivate all others
            TahunAjaran::where('id', '!=', $id)->update(['is_active' => false]);
            
            // Clear Cache
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_id');
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_name');
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran');
        }

        $tahun->update($request->all());

        return redirect()->route('admin.pengaturan.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        if ($tahun->is_active) {
            return back()->with('error', 'Tidak bisa menghapus Tahun Ajaran yang sedang aktif');
        }
        $tahun->delete();
        return back()->with('success', 'Tahun Ajaran berhasil dihapus');
    }
}
