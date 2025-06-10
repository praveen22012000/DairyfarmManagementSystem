
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h3>Monthly Milk Consumption per Product (Liters)</h3>

    <!-- Year Dropdown -->
    <form method="GET" action="{{ route('milk.consumption.product.monthly') }}" class="form-inline mb-4">
        <label for="year" class="mr-2">Select Year:</label>
        <select name="year" id="year" class="form-control mr-2">
            @foreach ($years as $yr)
                 @for ($i = now()->year; $i >= 2023; $i--)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">View Report</button>
    </form>

    <!-- Table View -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Month</th>
                @foreach ($products as $product)
                    <th>{{ $product }} (L)</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($tableData as $row)
                <tr>
                    <td>{{ $row['month'] }}</td>
                    @foreach ($products as $product)
                        <td>{{ number_format($row[$product], 2) }}</td>
                    @endforeach
                    
                </tr>
            @endforeach
        </tbody>
    </table>

   

        <div class="chart-container" style="position: relative; height:300px; width:100%">
            <canvas id="productChart"></canvas>
        </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('productChart').getContext('2d');
        
        // Prepare the data
        const labels = {!! json_encode(array_column($tableData, 'month')) !!};
        const products = {!! json_encode($products) !!};
        const datasets = [];
        
        @foreach ($products as $product)
            datasets.push({
                label: '{{ $product }}',
                data: {!! json_encode(array_column($tableData, $product)) !!},
                backgroundColor: '{{ sprintf('rgba(%d, %d, %d, 0.6)', rand(50,255), rand(50,255), rand(50,255)) }}',
                borderColor: '{{ sprintf('rgba(%d, %d, %d, 1)', rand(50,255), rand(50,255), rand(50,255)) }}',
                borderWidth: 1
            });
        @endforeach

        // Create the chart
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Milk Consumption per Product (in Liters)'
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Liters'
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
