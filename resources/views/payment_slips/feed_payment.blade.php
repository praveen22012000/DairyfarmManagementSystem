<!DOCTYPE html>
<html>
<head>
    <title>Payment Slip</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Dairy Farm Payment Slip</h2>
        <p>Reference Number: {{ $payment->reference_number }}</p>
        <p>Date: {{ $payment->payment_date }}</p>
    </div>

    <div class="details">
        <p><strong>Supplier Name:</strong> {{ $purchase->supplier->name }}</p>
        <p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
        <p><strong>Amount Paid:</strong> {{ $payment->payment_amount }} </p>
        <p><strong>Paid By:</strong> {{ $user->name }}</p>
    </div>
</body>
</html>
