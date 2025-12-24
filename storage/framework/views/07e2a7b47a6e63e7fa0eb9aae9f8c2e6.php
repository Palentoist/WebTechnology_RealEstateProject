

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Unit <?php echo e($unit->unit_number); ?></h2>
        <p><strong>Project:</strong> <?php echo e(optional($unit->project)->name); ?></p>
        <p><strong>Category:</strong> <?php echo e(optional($unit->category)->name); ?></p>
        <p><strong>Price:</strong> <?php echo e($unit->price); ?></p>
        <p><strong>Status:</strong> <?php echo e($unit->status); ?></p>
        <a href="<?php echo e(route('units.index')); ?>" class="btn">Back</a>
    </div>

    <div class="card">
        <h2>Bookings for this Unit</h2>
        <a href="<?php echo e(route('bookings.create')); ?>" class="btn btn-primary">New Booking</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Plan</th>
                <th>Booking Date</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $unit->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($booking->id); ?></td>
                    <td><?php echo e(optional($booking->user)->name); ?></td>
                    <td><?php echo e(optional($booking->installmentPlan)->plan_name); ?></td>
                    <td><?php echo e($booking->booking_date); ?></td>
                    <td><?php echo e($booking->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">No bookings yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Unit Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/units/show.blade.php ENDPATH**/ ?>