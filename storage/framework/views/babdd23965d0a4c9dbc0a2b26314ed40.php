

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Project: <?php echo e($project->name); ?></h2>
        <p><strong>Location:</strong> <?php echo e($project->location); ?></p>
        <p><strong>Total Units:</strong> <?php echo e($project->total_units); ?></p>
        <p><strong>Description:</strong> <?php echo e($project->description); ?></p>
        <p><strong>Admin:</strong> <?php echo e(optional($project->admin)->name); ?></p>
        <a href="<?php echo e(route('projects.index')); ?>" class="btn">Back to list</a>
    </div>

    <div class="card">
        <h2>Unit Categories</h2>
        <a href="<?php echo e(route('unit-categories.create')); ?>" class="btn btn-primary">Add Category</a>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Base Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $project->unitCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($category->name); ?></td>
                    <td><?php echo e($category->base_price); ?></td>
                    <td><a href="<?php echo e(route('unit-categories.show', $category)); ?>" class="btn">View</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3">No categories yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Units</h2>
        <a href="<?php echo e(route('units.create')); ?>" class="btn btn-primary">Add Unit</a>
        <table>
            <thead>
            <tr>
                <th>Number</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $project->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($unit->unit_number); ?></td>
                    <td><?php echo e(optional($unit->category)->name); ?></td>
                    <td><?php echo e($unit->price); ?></td>
                    <td><?php echo e($unit->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4">No units yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Installment Plans</h2>
        <a href="<?php echo e(route('installment-plans.create')); ?>" class="btn btn-primary">Add Plan</a>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Duration (months)</th>
                <th>Down Payment %</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $project->installmentPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($plan->plan_name); ?></td>
                    <td><?php echo e($plan->duration_months); ?></td>
                    <td><?php echo e($plan->down_payment_percentage); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3">No plans yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Project Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/projects/show.blade.php ENDPATH**/ ?>