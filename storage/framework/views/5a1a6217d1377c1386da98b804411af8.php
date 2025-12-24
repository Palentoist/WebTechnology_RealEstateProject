

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Unit Categories</h2>
        <a href="<?php echo e(route('unit-categories.create')); ?>" class="btn btn-primary">New Category</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Name</th>
                <th>Base Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($category->id); ?></td>
                    <td><?php echo e(optional($category->project)->name); ?></td>
                    <td><?php echo e($category->name); ?></td>
                    <td><?php echo e($category->base_price); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="<?php echo e(route('unit-categories.show', $category)); ?>" class="btn">View</a>
                            <a href="<?php echo e(route('unit-categories.edit', $category)); ?>" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">No categories found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo e($categories->links()); ?>

    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Unit Categories'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/unit_categories/index.blade.php ENDPATH**/ ?>