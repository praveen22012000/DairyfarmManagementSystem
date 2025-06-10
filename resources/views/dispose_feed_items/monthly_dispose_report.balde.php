@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h2>Feed Disposal Report - {{ $year }}</h2>

    <form method="GET" action="{{ route('feed.disposal.report') }}">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @for($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    <table border="1" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Feed Name</th>
                @foreach(range(1, 12) as $m)
                    <th>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableData as $row)
                <tr>
                    <td>{{ $row['feed_name'] }}</td>
                    @foreach(range(1, 12) as $m)
                        <td>{{ $row['month_' . $m] }}</td>
                    @endforeach
                    <td><strong>{{ $row['total'] }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                @foreach(range(1, 12) as $m)
                    <th>{{ $monthlyTotals[$m] }}</th>
                @endforeach
                <th>{{ array_sum($monthlyTotals) }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- Chart Section --}}
    <div>
        <canvas id="disposalChart"></canvas>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('disposalChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach(range(1, 12) as $m)
                    "{{ date('M', mktime(0, 0, 0, $m, 1)) }}",
                @endforeach
            ],
            datasets: [{
                label: 'Total Disposed Quantity (All Feeds)',
                data: [
                    @foreach(range(1, 12) as $m)
                        {{ $monthlyTotals[$m] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Quantity (Kg)' }
                }
            }
        }
    });
</script>
@endsection