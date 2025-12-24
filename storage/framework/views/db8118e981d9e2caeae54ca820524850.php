<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Payments</h2>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Booking</th>
                <th>Customer</th>
                <th>Installment #</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Received By</th>
                <th>Slip PDF</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($payment->id); ?></td>
                    <td><?php echo e(optional(optional(optional($payment->installmentItem)->schedule)->booking)->id); ?></td>
                    <td><?php echo e(optional(optional(optional(optional($payment->installmentItem)->schedule)->booking)->user)->name); ?></td>
                    <td><?php echo e(optional($payment->installmentItem)->installment_number); ?></td>
                    <td><?php echo e($payment->payment_date); ?></td>
                    <td><?php echo e($payment->amount); ?></td>
                    <td><?php echo e($payment->payment_method); ?></td>
                    <td><?php echo e(optional($payment->receivedBy)->name); ?></td>

                    <td style="white-space: nowrap;">
                        <a href="<?php echo e(route('payments.slip-pdf', $payment)); ?>" class="btn btn-primary" style="padding:6px 10px;">
                            📄 Slip PDF
                        </a>
                    </td>

                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="<?php echo e(route('payments.show', $payment)); ?>" class="btn">View</a>
                            <a href="<?php echo e(route('payments.edit', $payment)); ?>" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10">No payments recorded.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php echo e($payments->links()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', ['title' => 'Payments'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/payments/index.blade.php ENDPATH**/ ?>