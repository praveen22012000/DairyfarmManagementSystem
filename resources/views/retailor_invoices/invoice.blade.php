<h1 style='text-align:center'>Maruthi Industries</h1>
<h2 style='text-align:center'>Dairy Farm Management System</h2>
<p>Invoice #{{ $order->id }}</p>
<p>Date: {{ $order->created_at->format('Y-m-d') }}</p>

<p>Customer: {{ $order->customer_name }}</p>
<p>Address: {{ $order->customer_address }}</p>

<table border="1" cellpadding="5">
  <thead>
    <tr>
      <th>Item</th><th>Quantity</th><th>Price</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($order->retailor_order_item as $item)
      <tr>
        <td>{{ $item->milk_product->product_name }}</td>
        <td>{{ $item->ordered_quantity }}</td>
        <td>Rs.{{ $item->unit_price }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<p><strong>Total:</strong> Rs.{{ $order->total_amount }}</p>
