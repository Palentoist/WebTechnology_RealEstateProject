<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Slip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { margin-bottom: 18px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .sub { margin: 4px 0 0; color: #444; }
        .box { border: 1px solid #ddd; padding: 14px; border-radius: 6px; }
        .row { margin-bottom: 8px; }
        .label { font-weight: bold; display: inline-block; width: 140px; }
        .hr { border-top: 1px solid #eee; margin: 14px 0; }
        .small { font-size: 10px; color: #555; }
    </style>
</head>
<body>

<div class="header">
    <p class="title">Payment Slip</p>
    <p class="sub">Generated: {{ now()->format('Y-m-d H:i') }}</p>
</div>

<div class="box">
    <div class="row"><span class="label">Slip ID:</span> {{ $payment->id }}</div>

    {{-- Adjust these fields to match your DB columns --}}
    <div class="row"><span class="label">Amount:</span> {{ $payment->amount ?? 'N/A' }}</div>
    <div class="row"><span class="label">Status:</span> {{ $payment->status ?? 'N/A' }}</div>

    <div class="row">
        <span class="label">Payment Date:</span>
        {{ optional($payment->created_at)->format('Y-m-d H:i') }}
    </div>

    @if(isset($payment->reference))
        <div class="row"><span class="label">Reference:</span> {{ $payment->reference }}</div>
    @endif

    <div class="hr"></div>

    <div class="small">
        This slip is system-generated.
    </div>
</div>

</body>
</html>