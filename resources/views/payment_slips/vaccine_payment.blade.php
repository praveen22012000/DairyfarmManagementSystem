<!DOCTYPE html>
<html>
<head>
    <title>Payment Slip</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: center; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .header-container { 
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .logo { 
            width: 70px; 
            height: 70px; 
            margin-right: 15px; 
        }
        .farm-info { 
            flex: 1; 
        }
        .farm-name { 
            color: #1a73e8;
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }
        .farm-details {
            text-align: center;
            font-size: 15px;
            line-height: 1.5;
            color: #555;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
        }
        .payment-details {
            margin: 20px auto;
            width: 80%;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .payment-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .payment-details strong {
            color: #1a73e8;
        }
        .reference-number {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
        }
        .payment-date {
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <!-- Farm Info -->
        <div class="farm-info">
            <div class="farm-name">Maruthy Dairy Farm</div>
            <div class="farm-details">
                Address: Polikandy,Valvettithurai,Jaffna<br>
                Phone: 077 9425447 | Email: sgajaa1994@gmail.com
            </div>
        </div>
    </div>

    <div class="reference-number">
        <strong>Reference Number:</strong> {{ $payment->reference_number }}
    </div>
    <div class="payment-date">
        Date: {{ $payment->payment_date }}
    </div>

    <div class="payment-details">
        <p><strong>Supplier Name:</strong> {{ $purchase->supplier->name }}</p>
        <p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
        <p><strong>Amount Paid:</strong> {{ $payment->payment_amount }}</p>
        <p><strong>Paid By:</strong> {{ $user->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Vaccine ID</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->purchase_vaccine_items as $item)
            <tr>
                <td>{{ $item->vaccine_id }}</td>
                <td>{{ $item->vaccine->vaccine_name }}</td>
                <td>{{ $item->unit_price }}</td>
                <td>{{ $item->purchase_quantity }}</td>
                <td>{{ $item->unit_price * $item->purchase_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>