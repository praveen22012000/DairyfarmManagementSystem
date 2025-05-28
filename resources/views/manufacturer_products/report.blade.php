@if(isset($report) && $report->count())
    <h3>Manufacturing Report</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $data)
                <tr>
                    <td>{{ $data['product_name'] }}</td>
                    <td>{{ $data['total_quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No manufacturing data found for selected year and month.</p>
@endif
