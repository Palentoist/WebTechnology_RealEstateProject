

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Edit Payment</h2>

        <?php if($installmentItem): ?>
            <p>
                <strong>Booking:</strong>
                #<?php echo e(optional($installmentItem->schedule->booking)->id); ?>

                - <?php echo e(optional(optional($installmentItem->schedule->booking)->user)->name); ?>

            </p>

            <p>
                <strong>Installment #<?php echo e($installmentItem->installment_number); ?></strong>,
                Due: <?php echo e($installmentItem->due_date); ?>,
                Amount: <?php echo e($installmentItem->amount); ?>,
                Paid: <?php echo e($installmentItem->paid_amount); ?>

            </p>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('payments.update', $payment)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-row">
                <label>Payment Date</label>
                <input type="date" name="payment_date" value="<?php echo e(old('payment_date', $payment->payment_date)); ?>">
                <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-row">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" value="<?php echo e(old('amount', $payment->amount)); ?>">
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-row">
                <label>Payment Method</label>
                <input type="text" name="payment_method" value="<?php echo e(old('payment_method', $payment->payment_method)); ?>">
                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-row">
                <label>Transaction ID / Account No.</label>
                <input type="text" name="transaction_id" value="<?php echo e(old('transaction_id', $payment->transaction_id)); ?>">
                <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-row">
                <label>Bank Name</label>
                <input type="text" name="bank_name" value="<?php echo e(old('bank_name', $payment->bank_name)); ?>">
                <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
            <a href="<?php echo e(route('payments.index')); ?>" class="btn">Cancel</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Edit Payment'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/payments/edit.blade.php ENDPATH**/ ?>