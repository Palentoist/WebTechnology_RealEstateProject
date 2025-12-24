

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Category: <?php echo e($category->name); ?></h2>
        <p><strong>Project:</strong> <?php echo e(optional($category->project)->name); ?></p>
        <p><strong>Base Price:</strong> <?php echo e($category->base_price); ?></p>
        <p><strong>Description:</strong> <?php echo e($category->description); ?></p>
        <a href="<?php echo e(route('unit-categories.index')); ?>" class="btn">Back</a>
    </div>

    <div class="card">
        <h2>Units in this Category</h2>
        <a href="<?php echo e(route('units.create')); ?>" class="btn btn-primary">Add Unit</a>
        <table>
            <thead>
            <tr>
                <th>Unit #</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $category->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($unit->unit_number); ?></td>
                    <td><?php echo e($unit->price); ?></td>
                    <td><?php echo e($unit->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3">No units yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Unit Category'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/unit_categories/show.blade.php ENDPATH**/ ?>