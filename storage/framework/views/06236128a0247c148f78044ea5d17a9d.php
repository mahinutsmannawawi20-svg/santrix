

<?php $__env->startSection('title', 'Tenant Details'); ?>
<?php $__env->startSection('subtitle', 'Manage subscription and settings for ' . $pesantren->nama); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Back Link -->
    <a href="<?php echo e(route('owner.pesantren.index')); ?>" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to List
    </a>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-500/30">
                    <?php echo e(substr($pesantren->nama, 0, 1)); ?>

                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-slate-800"><?php echo e($pesantren->nama); ?></h1>
                    <div class="flex items-center mt-2 text-sm text-slate-500">
                        <span class="font-mono bg-slate-100 px-2 py-1 rounded text-slate-600 select-all"><?php echo e($pesantren->subdomain); ?></span>
                        <span class="mx-2">•</span>
                        <span>Joined <?php echo e($pesantren->created_at->format('F Y')); ?></span>
                    </div>
                </div>
            </div>
            <div class="mt-6 md:mt-0 flex gap-3">
                <a href="<?php echo e(route('owner.pesantren.edit', $pesantren->id)); ?>" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    Edit Subscription
                </a>
                
                <form action="<?php echo e(route('owner.pesantren.suspend', $pesantren->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to change the status of this tenant?');">
                    <?php echo csrf_field(); ?>
                    <?php if($pesantren->status === 'suspended'): ?>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/30">
                            Reactivate Tenant
                        </button>
                    <?php else: ?>
                        <button type="submit" class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 font-medium rounded-lg hover:bg-red-100 transition-colors">
                            Suspend Access
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <!-- Background Pattern -->
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-indigo-50 to-transparent pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Current Package</p>
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-slate-800 capitalize"><?php echo e($pesantren->package); ?></span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($pesantren->package == 'enterprise' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'); ?>">
                    Active
                </span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Expires On</p>
            <span class="text-xl font-bold text-slate-800"><?php echo e($pesantren->expired_at ? $pesantren->expired_at->format('d M Y') : '-'); ?></span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Santri</p>
            <span class="text-xl font-bold text-slate-800"><?php echo e($pesantren->santri_count); ?></span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Billing</p>
            <span class="text-xl font-bold text-slate-800">Rp <?php echo e(number_format($pesantren->invoices->where('status', 'paid')->sum('amount'), 0, ',', '.')); ?></span>
        </div>
    </div>

    <!-- Admin Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tenant Administrator</h3>
        <?php if($pesantren->admin): ?>
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                    <?php echo e(substr($pesantren->admin->name, 0, 2)); ?>

                </div>
                <div class="ml-4">
                    <p class="font-medium text-slate-800"><?php echo e($pesantren->admin->name); ?></p>
                    <p class="text-sm text-slate-500"><?php echo e($pesantren->admin->email); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p class="text-slate-400 italic">No admin user assigned to this tenant.</p>
        <?php endif; ?>
    </div>

    <!-- Content Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="border-b border-slate-100">
            <nav class="flex px-6" aria-label="Tabs">
                <button class="border-b-2 border-indigo-500 py-4 px-6 text-sm font-medium text-indigo-600">
                    Subscription History
                </button>
                <button class="border-b-2 border-transparent py-4 px-6 text-sm font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300">
                    Invoices
                </button>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            <!-- Subscription Table -->
            <div class="p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Package History</h3>
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Package</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Period</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $pesantren->subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800 capitalize"><?php echo e($sub->package_name); ?></td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    <?php echo e($sub->start_date->format('d M y')); ?> - <?php echo e($sub->end_date->format('d M y')); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($sub->status == 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-600'); ?>">
                                        <?php echo e($sub->status); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="px-4 py-4 text-center text-sm text-slate-400">No history available</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="p-6">
                 <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Recent Invoices</h3>
                 <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Invoice #</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                         <tbody class="bg-white divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $pesantren->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3 text-xs font-mono text-slate-600"><?php echo e($inv->invoice_number); ?></td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800">Rp <?php echo e(number_format($inv->amount, 0, ',', '.')); ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($inv->status == 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'); ?>">
                                        <?php echo e($inv->status); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="px-4 py-4 text-center text-sm text-slate-400">No invoices generated</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views/owner/pesantren/show.blade.php ENDPATH**/ ?>