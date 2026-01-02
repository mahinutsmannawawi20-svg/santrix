

<?php $__env->startSection('title', 'Activity Logs'); ?>
<?php $__env->startSection('subtitle', 'Audit trail of all owner actions on tenants.'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Timestamp</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <?php echo e($log->created_at->format('d M Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($log->causer): ?>
                            <div class="text-sm font-medium text-slate-800"><?php echo e($log->causer->name); ?></div>
                            <div class="text-xs text-slate-400"><?php echo e($log->causer->email); ?></div>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 italic">System</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700">
                        <?php echo e($log->description); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <?php if($log->subject): ?>
                            <a href="<?php echo e(route('owner.pesantren.show', $log->subject_id)); ?>" class="text-indigo-600 hover:underline">
                                <?php echo e($log->subject->nama ?? 'Pesantren #'.$log->subject_id); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-slate-400">Deleted</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if(!empty($log->properties)): ?>
                            <button type="button" onclick="alert(JSON.stringify(<?php echo e(json_encode($log->properties)); ?>, null, 2))" class="text-xs text-indigo-600 hover:underline">
                                View JSON
                            </button>
                        <?php else: ?>
                            <span class="text-xs text-slate-400">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-medium">No activity logs yet</h3>
                        <p class="text-slate-500 text-sm mt-1">Actions on tenants will appear here.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($logs->hasPages()): ?>
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        <?php echo e($logs->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views\owner\logs\index.blade.php ENDPATH**/ ?>