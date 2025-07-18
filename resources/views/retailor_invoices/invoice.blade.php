<!DOCTYPE html>
<html>
<head>
    <title>Retailor Invoice</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .header-container { 
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .logo { width: 70px; height: 70px; margin-right: 15px; }
        .farm-info { flex: 1; }
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
        }
        .report-period {
            text-align: center;
            margin-bottom: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <!-- Logo -->
     
        
        <!-- Farm Info -->
        <div class="farm-info">
            <div class="farm-name">Maruthi Dairy Farm</div>
            <div class="farm-details">
            Address: Polikandy,Valvettithurai,Jaffna<br>
                Phone: 077 9425447 | Email: sgajaa1994@gmail.com
            </div>
        </div>
    </div>

    <div class="report-title">Retailor Invoice Details</div>

    <p>Invoice #{{ $order->id }}</p>
<p>Date: {{ $order->created_at->format('Y-m-d') }}</p>

<p>Customer: {{ $order->user->name }}</p>
<p>Address: {{ $order->customer_address }}</p>

<table>
  <thead>
    <tr>
      <th>Item</th><th>Quantity</th><th>Price</th><th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($order->retailor_order_item as $item)
      <tr>
        <td>{{ $item->milk_product->product_name }}</td>
        <td>{{ $item->ordered_quantity }}</td>
        <td>Rs.{{ $item->unit_price }}</td>
        <td>{{ $item->ordered_quantity * $item->unit_price  }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<p><strong>Total:</strong> Rs.{{ $order->total_payable_amount }}</p>

    

</body>
</html>