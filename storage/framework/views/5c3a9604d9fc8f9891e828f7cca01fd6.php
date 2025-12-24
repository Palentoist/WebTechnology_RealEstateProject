<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Projects</h2>
        <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">New Project</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Total Units</th>
                <th>Admin</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($project->id); ?></td>
                    <td><?php echo e($project->name); ?></td>
                    <td><?php echo e($project->location); ?></td>
                    <td><?php echo e($project->total_units); ?></td>
                    <td><?php echo e(optional($project->admin)->name); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn">View</a>
                            <a href="<?php echo e(route('projects.edit', $project)); ?>" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">No projects found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo e($projects->links()); ?>

    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', ['title' => 'Projects'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/projects/index.blade.php ENDPATH**/ ?>