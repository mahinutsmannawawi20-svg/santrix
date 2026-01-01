

<?php $__env->startSection('title', 'Investigasi Pencairan'); ?>
<?php $__env->startSection('subtitle', 'Setujui atau tolak permintaan pencairan dana dari Pesantren'); ?>

<?php $__env->startSection('content'); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3">
                                    <?php echo e(substr($item->pesantren->nama ?? '?', 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900"><?php echo e($item->pesantren->nama ?? 'Unknown'); ?></div>
                                    <div class="text-xs text-slate-500"><?php echo e($item->pesantren->nspp ?? '-'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <?php echo e($item->created_at->format('d M Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">
                            Rp <?php echo e(number_format($item->amount, 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <div class="font-medium"><?php echo e($item->bank_name); ?></div>
                            <div class="text-xs"><?php echo e($item->account_number); ?> (<?php echo e($item->account_name); ?>)</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($item->status == 'pending'): ?>
                                <div class="flex items-center gap-2">
                                    <!-- Approve Form -->
                                    <form action="<?php echo e(route('owner.withdrawal.update', $item->id)); ?>" method="POST" onsubmit="return confirm('Setujui pencairan ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Reject Button (Trigger Modal) -->
                                    <button onclick="openRejectModal('<?php echo e($item->id); ?>', '<?php echo e($item->pesantren->nama); ?>', '<?php echo e(number_format($item->amount)); ?>')" 
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors">
                                        Tolak
                                    </button>
                                </div>
                            <?php elseif($item->status == 'approved'): ?>
                                <div class="flex flex-col">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 w-fit">
                                        Berhasil
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1"><?php echo e($item->updated_at->format('d/m/Y')); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 w-fit">
                                        Ditolak
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1">Note: <?php echo e($item->admin_note); ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                            <p>Belum ada permintaan pencairan.</p>
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

<!-- Reject Modal -->
<dialog id="rejectModal" class="modal rounded-2xl shadow-2xl p-0 backdrop:bg-slate-900/50 w-full max-w-md open:animate-fade-in">
    <div class="bg-white p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tolak Permintaan</h3>
        <p class="text-sm text-slate-600 mb-4">Saldo akan dikembalikan ke Pesantren <span id="rejectPesantrenName" class="font-bold"></span>.</p>
        
        <form id="rejectForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views/owner/withdrawal/index.blade.php ENDPATH**/ ?>