<!DOCTYPE html>
<html>
<head>
    <title>
    </title>
</head>
<body>
    <h2>Delivery Person Started Delivery
    </h2>

    <h3 style="color: green;">Delivery Person named {{$order->farm_labore->user->name}} started delivery for the order ID {{ $order->id }} ordered at {{ $order->ordered_date  }}

    </h3>

    <p>Retailor Name: {{ $order->user->name }}</p>
    <p>Store Name: {{ $order->user?->retailer?->store_name ?? 'Store name not available' }}</p>

    <p><Address>:Address: {{ $order->user->address }}</Address></p>
    
</table>

    
</body>
</html>