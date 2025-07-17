<!DOCTYPE html>
<html>
<head>
    <title>New Retailor Order Assignment
    </title>
</head>
<body>
    <h2>New Retailor Order Assignment</h2>

    <h3 style="color: green;">Hi {{ $retailor_order->farm_labore->user->name }},The Order ID {{ $retailor_order->id }} is Assinged to you.</h3>

    <p>Retailor Name: {{ $retailor_order->user->name }}</p>
    @if($retailor_order->user?->retailer?->store_name)
    <p>Store Name: {{ $retailor_order->user->retailer->store_name }}</p>
@else
    <p>Store Name: Not Available</p>
@endif

    <p><Address>:Address: {{ $retailor_order->user->address }}</Address></p>
    
</table>

    
</body>
</html>