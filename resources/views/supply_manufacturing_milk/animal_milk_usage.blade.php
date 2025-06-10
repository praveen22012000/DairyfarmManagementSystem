@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h2>Milk Consumption Report - {{ $year }}</h2>

    <!-- Added table-responsive wrapper for better mobile viewing -->
    <div class="table-responsive">
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Animal Name</th>
            @foreach(range(1, 12) as $month)
                <th>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tableData as $data)
            <tr>
                <td>{{ $data['animal_name'] }}</td>
                @foreach(range(1, 12) as $month)
                    <td>{{ $data['month_' . $month] }}</td>
                @endforeach
                <td><strong>{{ $data['total'] }}</strong></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Monthly Subtotal</th>
            @foreach(range(1, 12) as $month)
                <th><strong>{{ $totalPerMonth[$month] }}</strong></th>
            @endforeach
            <th><strong>{{ array_sum($totalPerMonth) }}</strong></th>
        </tr>
    </tfoot>
</table>

    </div>

    <!-- Added fixed height container for chart -->
    <div style="height: 400px; margin-top: 20px;">
        <canvas id="milkChart"></canvas>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('milkChart').getContext('2d');
        
        // Generate colors in JavaScript instead of PHP
        function getRandomColor() {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return {
                background: `rgba(${r}, ${g}, ${b}, 0.5)`,
                border: `rgba(${r}, ${g}, ${b}, 1)`
            };
        }

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                         'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    @foreach($tableData as $data)
                    {
                        label: '{{ $data['animal_name'] }}',
                        data: [
                            @foreach(range(1, 12) as $month)
                                {{ $data['month_' . $month] }},
                            @endforeach
                        ],
                        backgroundColor: getRandomColor().background,
                        borderColor: getRandomColor().border,
                        borderWidth: 1
                    },
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Milk Quantity (Liters)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection