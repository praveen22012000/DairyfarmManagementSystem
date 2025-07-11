<!DOCTYPE html>
<html>
<head>
    <title>Order Status Notification</title>
</head>
<body>
    <h2>Hello {{ $order->user->name }},</h2>

    <p>Your order (ID: {{ $order->id }}) has been 
        <strong>{{ $order->status }}</strong>.
        For further information, contact the Maruthi Farm management.
    </p>

    @if($order->status === 'Approved')
        <p style="color: green;"><strong>Your order has been accepted. You can make the payment.</strong></p>
    @elseif($order->status === 'Rejected')
        <p style="color: red;"><strong>Your order has been rejected. Please contact the farm management.</strong></p>
    @endif

    <p>Thank you for using our Dairy Farm System.</p>
</body>
</html>
