

<?php $__env->startSection('title', 'Billing & Subscription'); ?>
<?php $__env->startSection('page-title', 'Billing & Langganan'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
    <?php echo $__env->make('admin.partials.sidebar-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">
    
    <!-- Success/Warning Alerts -->
    <?php if(session('success')): ?>
        <div style="background: #ecfdf5; color: #047857; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #a7f3d0;">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
        <div style="background: #fef3c7; color: #92400e; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #fde68a;">
            <i data-feather="alert-triangle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;"><?php echo e(session('warning')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Header Subscription Status -->
    <div style="background: linear-gradient(120deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 140px; height: 140px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <?php
            $status = $subscription->status ?? 'expired';
            $isExpired = $subscription ? $subscription->expired_at->isPast() : true;
        ?>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; position: relative; z-index: 1;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 12px; backdrop-filter: blur(4px);">
                        <i data-feather="package" style="width: 28px; height: 28px; color: white;"></i>
                    </div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; margin: 0; color: white !important;">PAKET <?php echo e(strtoupper($subscription->package_name ?? 'PENDING')); ?></h1>
                    <?php if(!$isExpired): ?>
                    <div style="background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.3);">
                        <i data-feather="check" style="width: 12px; height: 12px; display: inline;"></i> AKTIF
                    </div>
                    <?php else: ?>
                    <div style="background: rgba(239, 68, 68, 0.2); padding: 6px 16px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(239, 68, 68, 0.3);">
                        <i data-feather="x-circle" style="width: 12px; height: 12px; display: inline;"></i> KADALUARSA
                    </div>
                    <?php endif; ?>
                </div>
                <p style="color: rgba(255,255,255,0.95) !important; font-size: 1rem; margin: 0; font-weight: 500;">
                    <?php if($subscription && !$isExpired): ?>
                        Berlaku hingga <?php echo e($subscription->expired_at->format('d F Y')); ?>

                    <?php else: ?>
                        Langganan tidak aktif atau sudah berakhir
                    <?php endif; ?>
                </p>
                
                <!-- Progress Bar -->
                <?php if($subscription && !$isExpired): ?>
                    <?php
                        $daysLeft = now()->diffInDays($subscription->expired_at);
                        $daysTotal = $subscription->started_at->diffInDays($subscription->expired_at);
                        $progressPercent = ($daysLeft / $daysTotal) * 100;
                    ?>
                    <div style="margin-top: 16px; max-width: 400px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.8);">Sisa Waktu</span>
                            <span style="font-size: 0.75rem; font-weight: 700; color: white;"><?php echo e($daysLeft); ?> hari lagi</span>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: white; height: 100%; border-radius: 999px; width: <?php echo e($progressPercent); ?>%; transition: width 1s ease;"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <a href="<?php echo e(route('admin.billing.plans')); ?>" style="background: white; color: #6366f1; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i data-feather="zap" style="width: 18px; height: 18px;"></i>
                    <?php echo e($subscription && $subscription->package_name === 'advance' ? 'Perpanjang Langganan' : 'Upgrade Sekarang'); ?>

                </a>
            </div>
        </div>
    </div>

    <!-- Invoice History Table -->
    <div style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
        <!-- Table Header -->
        <div style="padding: 24px 28px; border-bottom: 2px solid #f1f5f9;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Riwayat Transaksi</h2>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Semua invoice pembayaran langganan</p>
                </div>
                <div style="background: #f5f3ff; color: #7c3aed; padding: 6px 16px; border-radius: 999px; font-size: 0.875rem; font-weight: 700;">
                    <?php echo e($invoices->total()); ?> Invoice
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">No. Invoice</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; text-align: right; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 18px 24px;">
                            <span style="font-family: 'Courier New', monospace; font-size: 0.875rem; font-weight: 600; color: #334155;"><?php echo e($invoice->invoice_number); ?></span>
                        </td>
                        <td style="padding: 18px 24px;">
                            <div style="font-size: 0.875rem; font-weight: 600; color: #1e293b;"><?php echo e($invoice->created_at->format('d M Y')); ?></div>
                            <div style="font-size: 0.75rem; color: #94a3b8;"><?php echo e($invoice->created_at->format('H:i')); ?> WIB</div>
                        </td>
                        <td style="padding: 18px 24px;">
                            <span style="font-size: 1rem; font-weight: 700; color: #0f172a;">Rp <?php echo e(number_format($invoice->amount, 0, ',', '.')); ?></span>
                        </td>
                        <td style="padding: 18px 24px;">
                            <?php
                                $statusConfig = match($invoice->status) {
                                    'paid' => ['bg' => '#ecfdf5', 'color' => '#047857', 'text' => 'LUNAS'],
                                    'pending' => ['bg' => '#fef3c7', 'color' => '#92400e', 'text' => 'PENDING'],
                                    'failed' => ['bg' => '#fef2f2', 'color' => '#b91c1c', 'text' => 'GAGAL'],
                                    default => ['bg' => '#f1f5f9', 'color' => '#475569', 'text' => 'UNKNOWN'],
                                };
                            ?>
                            <div style="display: inline-block; background: <?php echo e($statusConfig['bg']); ?>; color: <?php echo e($statusConfig['color']); ?>; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                                <?php echo e($statusConfig['text']); ?>

                            </div>
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <a href="<?php echo e(route('admin.billing.show', $invoice->id)); ?>" style="background: #6366f1; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                                Detail
                                <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="padding: 60px 24px; text-align: center;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; opacity: 0.5;">
                                <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="inbox" style="width: 36px; height: 36px; color: #94a3b8;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">Belum Ada Transaksi</div>
                                    <p style="font-size: 0.875rem; color: #94a3b8; margin: 0;">Mulai dengan memilih paket langganan Anda</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($invoices->hasPages()): ?>
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            <?php echo e($invoices->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views\billing\index.blade.php ENDPATH**/ ?>