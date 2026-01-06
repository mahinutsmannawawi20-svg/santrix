<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $pesantrenId = auth()->user()->pesantren_id;
        $tahunAjaran = TahunAjaran::where('pesantren_id', $pesantrenId)
                        ->orderBy('nama', 'desc')
                        ->get();
        return view('admin.settings.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20', 
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        $pesantrenId = auth()->user()->pesantren_id;

        // Check if name exists for this pesantren
        if (TahunAjaran::where('pesantren_id', $pesantrenId)->where('nama', $request->nama)->exists()) {
             return back()->withErrors(['nama' => 'Nama tahun ajaran sudah ada.'])->withInput();
        }

        if ($request->is_active) {
            // Deactivate all others for this pesantren
            TahunAjaran::where('pesantren_id', $pesantrenId)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            
            // Clear Cache
            \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_' . $pesantrenId);
        }

        TahunAjaran::create([
            'pesantren_id' => $pesantrenId,
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.pengaturan.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pesantrenId = auth()->user()->pesantren_id;
        $tahun = TahunAjaran::where('pesantren_id', $pesantrenId)->findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'is_active' => 'required|boolean',
        ]);
        
        // Check duplicate name on other rows
        $exists = TahunAjaran::where('pesantren_id', $pesantrenId)
                    ->where('nama', $request->nama)
                    ->where('id', '!=', $id)
                    ->exists();

        if ($exists) {
            return back()->withErrors(['nama' => 'Nama tahun ajaran sudah digunakan.'])->withInput();
        }

        if ($request->is_active && !$tahun->is_active) {
            // Deactivate all others
            TahunAjaran::where('pesantren_id', $pesantrenId)
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);
                
             \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_' . $pesantrenId);
        }

        $tahun->update($request->only(['nama', 'tanggal_mulai', 'tanggal_selesai', 'is_active']));

        return redirect()->route('admin.pengaturan.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pesantrenId = auth()->user()->pesantren_id;
        // Use where to ensure tenant ownership
        $tahun = TahunAjaran::where('pesantren_id', $pesantrenId)->findOrFail($id);
        
        if ($tahun->is_active) {
            return back()->with('error', 'Tidak bisa menghapus Tahun Ajaran yang sedang aktif');
        }
        $tahun->delete();
        return back()->with('success', 'Tahun Ajaran berhasil dihapus');
    }
}
