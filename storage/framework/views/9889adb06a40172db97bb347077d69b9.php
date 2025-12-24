<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Slip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .box { border: 1px solid #000; padding: 12px; }
        .row { margin-bottom: 6px; }
        .label { display: inline-block; width: 140px; font-weight: bold; }
        h2 { margin: 0 0 10px 0; }
    </style>
</head>
<body>
    <h2>Payment Slip</h2>

    <div class="box">
        <div class="row"><span class="label">Slip #</span> <?php echo e($slip->slip_number); ?></div>
        <div class="row"><span class="label">Issued</span> <?php echo e($slip->issued_date); ?></div>

        <hr>

        <div class="row"><span class="label">Payment Date</span> <?php echo e($payment->payment_date); ?></div>
        <div class="row"><span class="label">Amount</span> <?php echo e(number_format($payment->amount, 2)); ?></div>
        <div class="row"><span class="label">Method</span> <?php echo e(strtoupper($payment->payment_method)); ?></div>
        <div class="row"><span class="label">Transaction ID</span> <?php echo e($payment->transaction_id ?? '-'); ?></div>
        <div class="row"><span class="label">Bank</span> <?php echo e($payment->bank_name ?? '-'); ?></div>

        <hr>

        <div class="row"><span class="label">Customer</span> <?php echo e($payment->installmentItem->schedule->booking->user->name); ?></div>
        <div class="row"><span class="label">Booking #</span> <?php echo e($payment->installmentItem->schedule->booking->id); ?></div>
        <div class="row"><span class="label">Installment #</span> <?php echo e($payment->installmentItem->installment_number); ?></div>
        <div class="row"><span class="label">Due Date</span> <?php echo e($payment->installmentItem->due_date); ?></div>

        <hr>

        <div class="row"><span class="label">Received By</span> <?php echo e($payment->receivedBy->name ?? '-'); ?></div>
    </div>
</body>
</html>
<?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/pdfs/payment-slip.blade.php ENDPATH**/ ?>