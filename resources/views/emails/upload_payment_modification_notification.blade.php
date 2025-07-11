<!DOCTYPE html>
<html>
<head>
    <title>Updated Payment Slip</title>
</head>
<body>
    <h2>Updated Payment Slip Submitted by Retailor {{ $order->user->name }}</h2>

    <h3 style="color: green;">The retailor {{ $order->user->name }} has re-uploaded a payment slip for Order ID {{ $order->id }} made at {{ $order->ordered_date }}

Please review the updated payment slip at your earliest convenience.</h3>

  

</table>

    
</body>
</html>