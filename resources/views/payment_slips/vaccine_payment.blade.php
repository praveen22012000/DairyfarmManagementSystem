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

     <table style="border: 1px solid black; border-collapse: collapse;">
                <th>Vaccine ID</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>

                @foreach($purchase->purchase_vaccine_items as $item)
                <tr>
                    <td>{{ $item->vaccine_id }}</td>
                    <td>{{  $item->vaccine->vaccine_name  }}</td>
                    <td>{{ $item->unit_price}}</td>
                    <td>{{  $item->purchase_quantity }}</td>
                    <td>{{ $item->unit_price * $item->purchase_quantity }}</td>
                </tr>

                @endforeach


        </table>
</body>
</html>
