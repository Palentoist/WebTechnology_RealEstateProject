<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Units</h2>
        <a href="<?php echo e(route('units.create')); ?>" class="btn btn-primary">New Unit</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Category</th>
                <th>Number</th>
                <th>Price</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($unit->id); ?></td>
                    <td><?php echo e(optional($unit->project)->name); ?></td>
                    <td><?php echo e(optional($unit->category)->name); ?></td>
                    <td><?php echo e($unit->unit_number); ?></td>
                    <td><?php echo e($unit->price); ?></td>
                    <td><?php echo e($unit->status); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="<?php echo e(route('units.show', $unit)); ?>" class="btn">View</a>
                            <a href="<?php echo e(route('units.edit', $unit)); ?>" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7">No units found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo e($units->links()); ?>

    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Units'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/units/index.blade.php ENDPATH**/ ?>