<!DOCTYPE html>
<html>
<head>
    <title>Order Delivery Notification</title>
</head>
<body>
    <h2 style="color: green;">Order Delivered Successfully!</h2>

    <h3>Hi {{ $order->user->name }}, your order has been delivered successfully.</h3>

    <h3 style="color: green;">
        The delivery person, {{ $order->farm_labore->user->name }}, has delivered Order ID {{ $order->id }} which was placed on {{ $order->ordered_date }}.
    </h3>

    <h4>Delivered on: {{ $order->delivered_at }}</h4>

    <p>Retailer Name: {{ $order->user->name }}</p>
    <p>Store Name: {{ $order->user?->retailer?->store_name ?? 'Store name not available' }}</p>
    <p>Address: {{ $order->user->address }}</p>
</body>
</html>
