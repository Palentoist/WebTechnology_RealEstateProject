<?php $__env->startSection('content'); ?>
<div class="card">
    
    <?php if(session('success')): ?>
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#d4edda; color:#155724;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('status')): ?>
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#d4edda; color:#155724;">
            ✅ <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#f8d7da; color:#721c24;">
            ❌ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom: 16px;">
        <div>
            <h2 style="margin:0;">Payment Details</h2>
            <div style="opacity:.8;">Payment ID: <?php echo e($payment->id); ?></div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
    <a href="<?php echo e(route('payments.index')); ?>"
       class="btn"
       style="display:inline-block; padding:10px 14px; border-radius:10px;">
        ⬅ Back to Payments
    </a>

    
    <a href="<?php echo e(route('payments.slip-pdf', $payment)); ?>"
       style="
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding:12px 18px;
            border-radius:14px;
            background:#22c55e;
            color:#ffffff;
            font-weight:800;
            font-size:14px;
            text-decoration:none;
            box-shadow:0 6px 18px rgba(34,197,94,.35);
            border:2px solid rgba(255,255,255,.18);
        ">
        <span style="font-size:18px; line-height:1;">📄</span>
        Generate / Download Slip PDF
    </a>

    
    <span style="opacity:.7; font-size:12px;">
        (Click to generate if missing, otherwise downloads)
    </span>
</div>
    </div>

    
    <div class="card" style="margin-bottom: 16px;">
        <h3 style="margin-top:0;">Payment Info</h3>
        <p><strong>Date:</strong> <?php echo e($payment->payment_date); ?></p>
        <p><strong>Amount:</strong> <?php echo e(number_format($payment->amount, 2)); ?></p>
        <p><strong>Method:</strong> <?php echo e(strtoupper($payment->payment_method)); ?></p>
        <p><strong>Transaction ID:</strong> <?php echo e($payment->transaction_id ?? '-'); ?></p>
        <p><strong>Bank Name:</strong> <?php echo e($payment->bank_name ?? '-'); ?></p>
        <p><strong>Received By:</strong> <?php echo e(optional($payment->receivedBy)->name ?? '-'); ?></p>
        <p><strong>Created At:</strong> <?php echo e($payment->created_at ?? '-'); ?></p>
    </div>

    
    <div class="card" style="margin-bottom: 16px;">
        <h3 style="margin-top:0;">Slip Info</h3>

        <?php if($payment->paymentSlip): ?>
            <p><strong>Slip Number:</strong> <?php echo e($payment->paymentSlip->slip_number ?? '-'); ?></p>
            <p><strong>Issued Date:</strong> <?php echo e($payment->paymentSlip->issued_date ?? '-'); ?></p>

            <p>
                <strong>PDF Status:</strong>
                <?php if($payment->paymentSlip->pdf_path): ?>
                    <span style="color:#28a745; font-weight:bold;">Generated</span>
                <?php else: ?>
                    <span style="color:#ffc107; font-weight:bold;">Not Generated (click button above)</span>
                <?php endif; ?>
            </p>

            <p><strong>PDF Path:</strong> <?php echo e($payment->paymentSlip->pdf_path ?? '-'); ?></p>
        <?php else: ?>
            <div style="padding: 12px; border-radius: 8px; background:#fff3cd; color:#856404;">
                ⚠ No slip record found yet. It will be created when you click “Generate / Download Slip PDF”.
            </div>
        <?php endif; ?>
    </div>

    
    <?php
        $item     = $payment->installmentItem ?? null;
        $schedule = $item?->schedule ?? null;
        $booking  = $schedule?->booking ?? null;
        $customer = $booking?->user ?? null;
    ?>

    <div class="card">
        <h3 style="margin-top:0;">Installment / Booking / Customer</h3>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Customer</h4>
                <p><strong>Name:</strong> <?php echo e($customer->name ?? '-'); ?></p>
                <p><strong>Email:</strong> <?php echo e($customer->email ?? '-'); ?></p>
                <p><strong>Phone:</strong> <?php echo e($customer->phone ?? '-'); ?></p>
            </div>

            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Booking</h4>
                <p><strong>Booking ID:</strong> <?php echo e($booking->id ?? '-'); ?></p>
                <p><strong>Status:</strong> <?php echo e($booking->status ?? '-'); ?></p>
                <p><strong>Created:</strong> <?php echo e($booking->created_at ?? '-'); ?></p>
            </div>

            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Installment Item</h4>
                <p><strong>Installment #:</strong> <?php echo e($item->installment_number ?? '-'); ?></p>
                <p><strong>Due Date:</strong> <?php echo e($item->due_date ?? '-'); ?></p>
                <p><strong>Amount:</strong> <?php echo e(isset($item->amount) ? number_format($item->amount, 2) : '-'); ?></p>
                <p><strong>Paid Amount:</strong> <?php echo e(isset($item->paid_amount) ? number_format($item->paid_amount, 2) : '-'); ?></p>
                <p><strong>Status:</strong> <?php echo e($item->status ?? '-'); ?></p>
            </div>
        </div>

        <div style="margin-top: 12px;">
            <?php if($booking && Route::has('bookings.show')): ?>
                <a class="btn" href="<?php echo e(route('bookings.show', $booking)); ?>">View Booking</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/payments/show.blade.php ENDPATH**/ ?>