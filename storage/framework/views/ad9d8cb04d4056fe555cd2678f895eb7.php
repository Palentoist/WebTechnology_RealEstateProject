<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Bookings</h2>
        <a href="<?php echo e(route('bookings.create')); ?>" class="btn btn-primary">New Booking</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Project</th>
                <th>Plan</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($booking->id); ?></td>
                    <td><?php echo e(optional($booking->user)->name); ?></td>
                    <td><?php echo e(optional($booking->unit)->unit_number); ?></td>
                    <td><?php echo e(optional(optional($booking->unit)->project)->name); ?></td>
                    <td><?php echo e(optional($booking->installmentPlan)->plan_name); ?></td>
                    <td><?php echo e($booking->booking_date); ?></td>
                    <td><?php echo e($booking->status); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="<?php echo e(route('bookings.show', $booking)); ?>" class="btn">View</a>
                            <a href="<?php echo e(route('bookings.edit', $booking)); ?>" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8">No bookings found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo e($bookings->links()); ?>

    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/bookings/index.blade.php ENDPATH**/ ?>