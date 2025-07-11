<!DOCTYPE html>
<html>
<head>
    <title>
    </title>
</head>
<body>
    <h2>Re Assign Delivery Person</h2>

    <h3 style="color: green;">Hi {{ $order->farm_labore->user->name }},The Order ID {{ $order->id }} is Assinged to you.</h3>

    <p>Retailor Name: {{ $order->user->name }}</p>
    <p>Store Name: {{ $order->user?->retailer?->store_name ?? 'Store name not available' }}</p>

    <p><Address>: {{ $order->user->address }}</Address></p>
    
</table>

    
</body>
</html>