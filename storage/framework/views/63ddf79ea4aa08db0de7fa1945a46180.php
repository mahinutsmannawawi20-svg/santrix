

<?php $__env->startSection('title', 'Pencairan Dana'); ?>
<?php $__env->startSection('subtitle', 'Kelola pencairan dana dari Payment Gateway Syahriah'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Saldo Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/30">
            <h3 class="text-indigo-100 text-sm font-medium mb-1">Saldo Payment Gateway</h3>
            <div class="flex items-baseline gap-1">
                <span class="text-sm font-medium opacity-80">Rp</span>
                <span class="text-3xl font-bold"><?php echo e(number_format($pesantren->saldo_pg, 0, ',', '.')); ?></span>
            </div>
            <p class="text-xs text-indigo-200 mt-2 opacity-80">Siap dicairkan ke rekening terdaftar.</p>
        </div>

        <!-- Bank Info Card -->
        <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-3 uppercase tracking-wider">Rekening Penerima</h3>
            <?php if($pesantren->bank_name): ?>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <i data-feather="credit-card"></i>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-slate-800"><?php echo e($pesantren->bank_name); ?> - <?php echo e($pesantren->account_number); ?></div>
                        <div class="text-slate-500"><?php echo e($pesantren->account_name); ?></div>
                    </div>
                    <div class="ml-auto">
                        <!-- Redirect to existing settings route, assuming it exists or handled by admin settings -->
                         <!-- Adjust route if needed, currently based on context -->
                         <a href="<?php echo e(route('admin.settings.pesantren')); ?>" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Ubah</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-2">
                    <p class="text-slate-500 mb-2">Belum ada rekening terdaftar.</p>
                    <a href="<?php echo e(route('admin.settings.pesantren')); ?>" class="text-indigo-600 font-medium hover:underline">Tambahkan Rekening</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions & History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Riwayat Penarikan</h3>
            
            <button onclick="document.getElementById('withdrawModal').showModal()" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                <?php if($pesantren->saldo_pg < 50000 || !$pesantren->bank_name): ?> disabled <?php endif; ?>>
                <i data-feather="plus-circle" class="w-4 h-4"></i>
                Tarik Dana
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Rekening Tujuan</th>
                        <th class="px-6 py-4">Admin Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <?php echo e($item->created_at->format('d M Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">
                            Rp <?php echo e(number_format($item->amount, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($item->status == 'pending'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Menunggu
                                </span>
                            <?php elseif($item->status == 'approved'): ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    Berhasil
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <?php echo e($item->bank_name); ?> - <?php echo e($item->account_number); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 italic">
                            <?php echo e($item->admin_note ?? '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                            <p>Belum ada riwayat penarikan dana.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100">
            <?php echo e($withdrawals->links()); ?>

        </div>
    </div>
</div>

<!-- Modal Withdrawal -->
<dialog id="withdrawModal" class="modal rounded-2xl shadow-2xl p-0 backdrop:bg-slate-900/50 w-full max-w-md open:animate-fade-in">
    <div class="bg-white p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-slate-800">Tarik Dana</h3>
            <button onclick="document.getElementById('withdrawModal').close()" class="text-slate-400 hover:text-slate-600">
                <i data-feather="x"></i>
            </button>
        </div>

        <form action="<?php echo e(route('admin.withdrawal.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6">
                <p class="text-sm text-indigo-800 mb-1">Saldo Tersedia:</p>
                <p class="text-xl font-bold text-indigo-700">Rp <?php echo e(number_format($pesantren->saldo_pg, 0, ',', '.')); ?></p>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Penarikan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-500 text-sm">Rp</span>
                        <input type="number" name="amount" min="50000" max="<?php echo e($pesantren->saldo_pg); ?>" required 
                            class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Minimal Rp 50.000</p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('withdrawModal').close()" class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</dialog>

<style>
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views\admin\withdrawal\index.blade.php ENDPATH**/ ?>