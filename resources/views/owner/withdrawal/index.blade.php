@extends('owner.layouts.app')

@section('title', 'Investigasi Pencairan')
@section('subtitle', 'Setujui atau tolak permintaan pencairan dana dari Pesantren')

@section('content')
<div class="space-y-6">

    <!-- Actions & History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Daftar Permintaan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Pesantren</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Rekening Tujuan</th>
                        <th class="px-6 py-4">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3">
                                    {{ substr($item->pesantren->nama ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ $item->pesantren->nama ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->pesantren->nspp ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <div class="font-medium">{{ $item->bank_name }}</div>
                            <div class="text-xs">{{ $item->account_number }} ({{ $item->account_name }})</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status == 'pending')
                                <div class="flex items-center gap-2">
                                    <!-- Approve Form -->
                                    <form action="{{ route('owner.withdrawal.update', $item->id) }}" method="POST" onsubmit="return confirm('Setujui pencairan ini?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Reject Button (Trigger Modal) -->
                                    <button onclick="openRejectModal('{{ $item->id }}', '{{ $item->pesantren->nama }}', '{{ number_format($item->amount) }}')" 
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">
                                        Tolak
                                    </button>
                                </div>
                            @elseif($item->status == 'approved')
                                <div class="flex flex-col">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 w-fit">
                                        Berhasil
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1">{{ $item->updated_at->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <div class="flex flex-col">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 w-fit">
                                        Ditolak
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1">Note: {{ $item->admin_note }}</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                            <p>Belum ada permintaan pencairan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
<dialog id="rejectModal" class="modal rounded-2xl shadow-2xl p-0 backdrop:bg-slate-900/50 w-full max-w-md open:animate-fade-in">
    <div class="bg-white p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tolak Permintaan</h3>
        <p class="text-sm text-slate-600 mb-4">Saldo akan dikembalikan ke Pesantren <span id="rejectPesantrenName" class="font-bold"></span>.</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Alasan Penolakan</label>
                <textarea name="admin_note" rows="3" required class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-red-500 outline-none placeholder:text-slate-400" placeholder="Contoh: Nama rekening tidak sesuai..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').close()" class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Tolak Permintaan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openRejectModal(id, pesantrenName, amount) {
        document.getElementById('rejectPesantrenName').innerText = pesantrenName;
        // Construct Route manually since JS can't use blade route param easily without placeholder
        // Assuming route is owner/withdrawal/{id}
        const form = document.getElementById('rejectForm');
        form.action = "/owner/withdrawal/" + id; 
        
        document.getElementById('rejectModal').showModal();
    }
</script>

<style>
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
    }
</style>
@endsection
