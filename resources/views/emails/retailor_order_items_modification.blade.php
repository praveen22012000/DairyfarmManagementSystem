<!DOCTYPE html>
<html>
<head>
    <title>Retailor Order Notification</title>
</head>
<body>
    <h2>New Retailor Order</h2>
<p>Order ID: {{ $retailororder->id }}</p>
<p>Total Amount: {{ $retailororder->total_amount }}</p>
<p>Payable Amount: {{ $retailororder->total_payable_amount }}</p>
<p>Retailor :{{ $retailororder->user->name }}</p>

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">ID</th>
            <th style="border: 1px solid black; padding: 8px;">Product Name</th>
            <th style="border: 1px solid black; padding: 8px;">Ordered Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($retailor_order_items as $retailor_order_item)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{ $retailor_order_item->id }}</td>
            <td style="border: 1px solid black; padding: 8px;">{{ $retailor_order_item->milk_product->product_name }}</td>
            <td style="border: 1px solid black; padding: 8px;">{{ $retailor_order_item->ordered_quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    
</body>
</html>