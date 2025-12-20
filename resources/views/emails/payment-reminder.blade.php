<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .header {
            background: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .details {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .amount {
            font-size: 22px;
            font-weight: bold;
            color: #4CAF50;
        }
        .footer {
            background: #fafafa;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>💳 Payment Reminder</h1>
    </div>

    <div class="content">
        <p>
            Dear <strong>{{ $customer->name }}</strong>,
        </p>

        <p>
            {{ $reminder->msg }}
        </p>

        <div class="details">
            <h3>📋 Installment Details</h3>
            <p>
                <strong>Installment #:</strong>
                {{ $installmentItem->installment_number }}
            </p>
            <p>
                <strong>Due Date:</strong>
                {{ \Carbon\Carbon::parse($installmentItem->due_date)->format('F d, Y') }}
            </p>
            <p>
                <strong>Amount Due:</strong>
                <span class="amount">
                    Rs. {{ number_format($installmentItem->amount, 2) }}
                </span>
            </p>
            <p>
                <strong>Paid So Far:</strong>
                Rs. {{ number_format($installmentItem->paid_amount, 2) }}
            </p>
            <p>
                <strong>Remaining Amount:</strong>
                Rs. {{ number_format($installmentItem->amount - $installmentItem->paid_amount, 2) }}
            </p>
        </div>

        <div class="details">
            <h3>🏢 Booking Information</h3>
            <p>
                <strong>Booking ID:</strong> #{{ $booking->id }}
            </p>
            <p>
                <strong>Unit Number:</strong>
                {{ optional($booking->unit)->unit_number ?? 'N/A' }}
            </p>
            <p>
                <strong>Project:</strong>
                {{ optional(optional($booking->unit)->project)->name ?? 'N/A' }}
            </p>
        </div>

        <p>
            Please ensure that your payment is completed on or before the due date
            to avoid any inconvenience.
        </p>

        <p>
            Thank you for choosing our services.
        </p>
    </div>

    <div class="footer">
        <p>
            This is an automated payment reminder from the Real Estate Management System.
        </p>
        <p>
            &copy; {{ date('Y') }} Real Estate Management System
        </p>
    </div>
</div>

</body>
</html>
