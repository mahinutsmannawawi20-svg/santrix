

<?php $__env->startSection('title', 'Data Pesantren'); ?>
<?php $__env->startSection('subtitle', 'Manage all registered tenants and their subscriptions.'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Filters & Search -->
    <div class="p-6 border-b border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="<?php echo e(route('owner.pesantren.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div class="relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search tenant..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64 placeholder-slate-400">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-600 bg-white">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expired</option>
                    <option value="suspended" <?php echo e(request('status') == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                </select>

                <select name="package" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-600 bg-white">
                    <option value="">All Packages</option>
                    <option value="basic" <?php echo e(request('package') == 'basic' ? 'selected' : ''); ?>>Basic</option>
                    <option value="advance" <?php echo e(request('package') == 'advance' ? 'selected' : ''); ?>>Advance</option>
                    <option value="enterprise" <?php echo e(request('package') == 'enterprise' ? 'selected' : ''); ?>>Enterprise</option>
                </select>
            </div>
        </form>
        
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
                Export Data
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Pesantren</th>
                    <th class="px-6 py-4">Subdomain</th>
                    <th class="px-6 py-4">Admin</th>
                    <th class="px-6 py-4">Package</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Expired At</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pesantrens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800"><?php echo e($p->nama); ?></div>
                        <div class="text-xs text-slate-500">ID: #<?php echo e($p->id); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="http://<?php echo e($p->subdomain); ?>.santrix.my.id" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            <?php echo e($p->subdomain); ?>

                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($p->admin): ?>
                            <div class="text-sm text-slate-700"><?php echo e($p->admin->name); ?></div>
                            <div class="text-xs text-slate-400"><?php echo e($p->admin->email); ?></div>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 italic">No admin</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($p->package == 'enterprise' ? 'bg-purple-100 text-purple-800' : ($p->package == 'advance' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?> capitalize">
                            <?php echo e($p->package); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                            $isExpired = $p->expired_at && $p->expired_at < now();
                            $isExpiringSoon = $p->expired_at && $p->expired_at >= now() && $p->expired_at <= now()->addDays(14);
                            
                            if($p->status == 'suspended') {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusLabel = 'Suspended';
                            } elseif($isExpired) {
                                $statusClass = 'bg-amber-100 text-amber-800';
                                $statusLabel = 'Expired';
                            } elseif($isExpiringSoon) {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusLabel = 'Expiring Soon';
                            } else {
                                $statusClass = 'bg-emerald-100 text-emerald-800';
                                $statusLabel = 'Active';
                            }
                        ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                            <?php echo e($statusLabel); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        <?php echo e($p->expired_at ? $p->expired_at->format('d M Y') : '-'); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        <?php echo e($p->created_at->format('d M Y')); ?>

                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?php echo e(route('owner.pesantren.show', $p->id)); ?>" class="inline-flex items-center px-3 py-1.5 border border-slate-200 shadow-sm text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-medium">No tenants found</h3>
                        <p class="text-slate-500 text-sm mt-1">Try adjusting your search or filters.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($pesantrens->hasPages()): ?>
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        <?php echo e($pesantrens->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views\owner\pesantren\index.blade.php ENDPATH**/ ?>