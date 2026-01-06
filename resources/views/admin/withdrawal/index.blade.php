@extends('layouts.app')

@section('title', 'Pencairan Dana')
@section('page-title', 'Withdrawal Saldo')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 30%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i data-feather="download" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0;">Saldo Payment Gateway</h1>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin: 4px 0 0 0;">Kelola pencairan dana dari hasil pembayaran Syahriah</p>
                </div>
            </div>
            <div style="display: flex; align-items: baseline; gap: 8px;">
                <span style="font-size: 0.875rem; font-weight: 500;">Rp</span>
                <span style="font-size: 2.5rem; font-weight: 900;">{{ number_format($pesantren->saldo_pg ?? 0, 0, ',', '.') }}</span>
            </div>
            <p style="font-size: 0.8rem; opacity: 0.8; margin-top: 8px;">Siap dicairkan ke rekening terdaftar.</p>
        </div>
    </div>

    <!-- Bank Info & Actions Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        
        <!-- Bank Info Card -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 16px 0;">Rekening Penerima</h3>
            
            @if($pesantren->bank_name)
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="credit-card" style="width: 24px; height: 24px; color: #64748b;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $pesantren->bank_name }}</p>
                        <p style="font-size: 1rem; color: #475569; margin: 4px 0 0 0;">{{ $pesantren->account_number }}</p>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 4px 0 0 0;">a.n. {{ $pesantren->account_name }}</p>
                    </div>
                    <a href="{{ route('admin.pengaturan') }}" style="background: #f1f5f9; color: #6366f1; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem;">Ubah</a>
                </div>
            @else
                <div style="text-align: center; padding: 24px;">
                    <div style="width: 64px; height: 64px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i data-feather="alert-circle" style="width: 32px; height: 32px; color: #f59e0b;"></i>
                    </div>
                    <p style="color: #64748b; margin: 0 0 16px 0;">Belum ada rekening terdaftar.</p>
                    <a href="{{ route('admin.pengaturan') }}" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600;">Tambahkan Rekening</a>
                </div>
            @endif
        </div>

        <!-- Quick Action Card -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 16px 0;">Penarikan Dana</h3>
            
            @if($pesantren->saldo_pg >= 50000 && $pesantren->bank_name)
                <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid #a7f3d0; border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <p style="color: #047857; font-weight: 600; margin: 0 0 8px 0;">✓ Saldo Anda memenuhi syarat penarikan</p>
                    <p style="color: #059669; font-size: 0.875rem; margin: 0;">Minimal Rp 50.000 untuk mengajukan penarikan.</p>
                </div>
                <button onclick="document.getElementById('withdrawModal').showModal()" style="width: 100%; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 14px 24px; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                    <i data-feather="plus-circle" style="width: 20px; height: 20px;"></i>
                    Tarik Dana Sekarang
                </button>
            @else
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 20px;">
                    <p style="color: #92400e; font-weight: 600; margin: 0 0 8px 0;">⚠️ Tidak dapat melakukan penarikan</p>
                    <p style="color: #a16207; font-size: 0.875rem; margin: 0;">
                        @if(!$pesantren->bank_name)
                            Silakan tambahkan rekening penerima terlebih dahulu.
                        @else
                            Saldo minimal Rp 50.000 untuk mengajukan penarikan.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- History Table -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Riwayat Penarikan</h3>
                <p style="font-size: 0.875rem; color: #64748b; margin: 4px 0 0 0;">Semua permintaan pencairan dana Anda</p>
            </div>
            <div style="background: #f5f3ff; color: #7c3aed; padding: 6px 16px; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                {{ $withdrawals->total() }} Transaksi
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jumlah</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Rekening Tujuan</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px 24px;">
                            <p style="font-weight: 600; color: #1e293b; margin: 0;">{{ $item->created_at->format('d M Y') }}</p>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin: 2px 0 0 0;">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td style="padding: 16px 24px;">
                            <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($item->status == 'pending')
                                <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">⏳ Menunggu</span>
                            @elseif($item->status == 'approved')
                                <span style="background: #d1fae5; color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">✓ Berhasil</span>
                            @else
                                <span style="background: #fee2e2; color: #b91c1c; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">✕ Ditolak</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px;">
                            <p style="color: #475569; margin: 0;">{{ $item->bank_name }}</p>
                            <p style="font-size: 0.875rem; color: #94a3b8; margin: 2px 0 0 0;">{{ $item->account_number }}</p>
                        </td>
                        <td style="padding: 16px 24px;">
                            <p style="color: #64748b; font-style: italic; margin: 0;">{{ $item->admin_note ?? '-' }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 60px 24px; text-align: center;">
                            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i data-feather="inbox" style="width: 40px; height: 40px; color: #94a3b8;"></i>
                            </div>
                            <p style="font-size: 1rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0;">Belum Ada Riwayat</p>
                            <p style="font-size: 0.875rem; color: #94a3b8; margin: 0;">Anda belum pernah melakukan penarikan dana.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Withdrawal Modal -->
<dialog id="withdrawModal" style="border: none; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); padding: 0; max-width: 480px; width: 90%;">
    <div style="background: white; padding: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Tarik Dana</h3>
            <button onclick="document.getElementById('withdrawModal').close()" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i data-feather="x" style="width: 20px; height: 20px; color: #64748b;"></i>
            </button>
        </div>

        <form action="{{ route('admin.withdrawal.store') }}" method="POST">
            @csrf
            
            <div style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <p style="color: #4338ca; font-size: 0.875rem; margin: 0 0 4px 0;">Saldo Tersedia</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: #4f46e5; margin: 0;">Rp {{ number_format($pesantren->saldo_pg ?? 0, 0, ',', '.') }}</p>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Jumlah Penarikan</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-weight: 500;">Rp</span>
                    <input type="number" name="amount" min="50000" max="{{ $pesantren->saldo_pg ?? 0 }}" required placeholder="Masukkan jumlah" style="width: 100%; padding: 14px 16px 14px 48px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-weight: 600;">
                </div>
                <p style="font-size: 0.75rem; color: #64748b; margin: 8px 0 0 0;">Minimal Rp 50.000</p>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="document.getElementById('withdrawModal').close()" style="flex: 1; background: #f1f5f9; color: #64748b; padding: 14px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Batal</button>
                <button type="submit" style="flex: 1; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 14px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</dialog>

<style>
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
</style>
@endsection
