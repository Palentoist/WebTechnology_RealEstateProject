

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Booking #<?php echo e($booking->id); ?></h2>
        <p><strong>Customer:</strong> <?php echo e(optional($booking->user)->name); ?> (<?php echo e(optional($booking->user)->email); ?>)</p>
        <p><strong>Unit:</strong> <?php echo e(optional($booking->unit)->unit_number); ?> - <?php echo e(optional(optional($booking->unit)->project)->name); ?></p>
        <p><strong>Plan:</strong> <?php echo e(optional($booking->installmentPlan)->plan_name); ?></p>
        <p><strong>Booking Date:</strong> <?php echo e($booking->booking_date); ?></p>
        <p><strong>Total Amount:</strong> <?php echo e($booking->total_amount); ?></p>
        <p><strong>Down Payment:</strong> <?php echo e($booking->down_payment); ?></p>
        <p><strong>Status:</strong> <?php echo e($booking->status); ?></p>
        <a href="<?php echo e(route('bookings.index')); ?>" class="btn">Back</a>
    </div>

    <?php if($booking->installmentSchedule): ?>
        <div class="card">
            <h2>Installment Schedule</h2>
            <p><strong>Total Installments:</strong> <?php echo e($booking->installmentSchedule->total_installments); ?></p>
            <p><strong>Period:</strong> <?php echo e($booking->installmentSchedule->start_date); ?> - <?php echo e($booking->installmentSchedule->end_date); ?></p>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $booking->installmentSchedule->installmentItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->installment_number); ?></td>
                        <td><?php echo e($item->due_date); ?></td>
                        <td><?php echo e($item->amount); ?></td>
                        <td><?php echo e($item->paid_amount); ?></td>
                        <td><?php echo e($item->status); ?></td>
                        <td>
                            <a href="<?php echo e(route('payments.create', ['installment_item_id' => $item->id])); ?>" class="btn">Add Payment</a>
                            <a href="<?php echo e(route('reminders.create', ['installment_item_id' => $item->id])); ?>" class="btn">Create Reminder</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Booking Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/bookings/show.blade.php ENDPATH**/ ?>