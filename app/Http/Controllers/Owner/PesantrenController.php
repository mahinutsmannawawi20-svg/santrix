<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PesantrenController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesantren::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($qa) use ($search) {
                      $qa->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter: Status
        if ($request->filled('status')) {
             $today = now();
             if ($request->status == 'active') {
                 $query->whereDate('expired_at', '>=', $today)->where('status', 'active');
             } elseif ($request->status == 'expired') {
                 $query->whereDate('expired_at', '<', $today);
             } elseif ($request->status == 'suspended') {
                 $query->where('status', 'suspended');
             }
        }

        // Filter: Package
        if ($request->filled('package')) {
            $query->where('package', $request->package);
        }

        $pesantrens = $query->with('admin')->latest()->paginate(20);

        $packages = \App\Models\Package::orderBy('sort_order')->get();
        return view('owner.pesantren.index', compact('pesantrens', 'packages'));
    }

    public function show($id)
    {
        $pesantren = Pesantren::with(['subscriptions', 'invoices', 'admin'])->findOrFail($id);
        return view('owner.pesantren.show', compact('pesantren'));
    }

    public function edit($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        return view('owner.pesantren.edit', compact('pesantren'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'package' => 'required|in:basic,advance,enterprise,trial',
            'expired_at' => 'required|date',
            'status' => 'required|in:active,suspended',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:100',
        ];

        // Advance Package Funding Requirement
        if ($request->package === 'advance' || $request->package === 'enterprise') {
            $rules['bank_name'] = 'required';
            $rules['account_number'] = 'required';
            $rules['account_name'] = 'required';
        }

        $request->validate($rules);

        $pesantren = Pesantren::findOrFail($id);
        $pesantren->update([
            'package' => $request->package,
            'expired_at' => $request->expired_at,
            'status' => $request->status,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        ActivityLog::logActivity(
            'Updated subscription for tenant: ' . $pesantren->nama,
            $pesantren,
            ['old' => $pesantren->getOriginal(), 'new' => $pesantren->getAttributes()],
            'updated'
        );

        return redirect()->route('owner.pesantren.show', $id)->with('success', 'Tenant updated successfully.');
    }

    public function suspend($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        
        $newStatus = $pesantren->status === 'suspended' ? 'active' : 'suspended';
        $pesantren->update(['status' => $newStatus]);
        
        $message = $newStatus === 'suspended' ? 'Tenant has been suspended.' : 'Tenant has been reactivated.';

        ActivityLog::logActivity(
            $newStatus === 'suspended' ? 'Suspended tenant: ' . $pesantren->nama : 'Reactivated tenant: ' . $pesantren->nama,
            $pesantren,
            ['status' => $newStatus],
            $newStatus === 'suspended' ? 'suspended' : 'reactivated'
        );

        return back()->withErrors(['message' => $message]);
    }

    public function destroy($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        $this->deletePesantrenData($pesantren);
        return redirect()->route('owner.pesantren.index')->with('success', 'Tenant ' . $pesantren->nama . ' and ALL its data have been deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pesantrens,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $pesantren = Pesantren::find($id);
            if ($pesantren) {
                $this->deletePesantrenData($pesantren);
                $count++;
            }
        }

        return redirect()->route('owner.pesantren.index')->with('success', $count . ' Tenants and their data have been deleted successfully.');
    }

    private function deletePesantrenData(Pesantren $pesantren)
    {
        // Log activity
        ActivityLog::logActivity(
            'Deleted tenant: ' . $pesantren->nama,
            $pesantren,
            ['id' => $pesantren->id, 'nama' => $pesantren->nama, 'subdomain' => $pesantren->subdomain],
            'deleted'
        );

        // MANUAL CASCADING DELETE
        
        // 1. Delete Santri and their relations
        $santriIds = $pesantren->santri()->pluck('id');
        
        if ($santriIds->count() > 0) {
            \App\Models\NilaiSantri::whereIn('santri_id', $santriIds)->delete();
            \App\Models\MutasiSantri::whereIn('santri_id', $santriIds)->delete();
            \App\Models\UjianMingguan::whereIn('santri_id', $santriIds)->delete();
            \App\Models\AbsensiSantri::whereIn('santri_id', $santriIds)->delete();
            \App\Models\Syahriah::whereIn('santri_id', $santriIds)->delete();
            $pesantren->santri()->delete();
        }

        // 2. Delete Academic & Infrastructure Data
        $asramaIds = $pesantren->asrama()->pluck('id');
        if ($asramaIds->count() > 0) {
            \App\Models\Kobong::whereIn('asrama_id', $asramaIds)->delete();
            $pesantren->asrama()->delete();
        }

        $pesantren->kelas()->delete();

        $mapelIds = $pesantren->mataPelajaran()->pluck('id');
        if ($mapelIds->count() > 0) {
            \App\Models\JadwalPelajaran::whereIn('mapel_id', $mapelIds)->delete();
        }
        $pesantren->mataPelajaran()->delete();
        
        // 3. Delete Billing & Subscriptions
        $pesantren->invoices()->delete();
        $pesantren->subscriptions()->delete();
        $pesantren->withdrawals()->delete();

        // 4. Delete Financial & Operational Records
        \App\Models\Pemasukan::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\Pengeluaran::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\Pegawai::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\TahunAjaran::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\KalenderPendidikan::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\IjazahSetting::where('pesantren_id', $pesantren->id)->delete();
        \App\Models\ReportSettings::where('pesantren_id', $pesantren->id)->delete();

        // 5. Delete Users (Admin/Staff)
        \App\Models\User::where('pesantren_id', $pesantren->id)->delete();

        // 6. Finally, Delete the Tenant
        $pesantren->delete();
    }
}
