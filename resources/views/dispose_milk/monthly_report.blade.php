@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h3>Monthly Disposed Milk Report</h3>

    <!-- Year Dropdown Form -->
    <!-- Year Filter -->
    <form method="GET" action="{{ route('dispose_milk_records_monthly.report') }}" class="form-inline mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" class="form-control mr-2">
            @for ($i = now()->year; $i >= 2023; $i--)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <button type="submit" class="btn btn-success">View Report</button>

    </form>

    @if ($year)
        <h5 class="mt-4">Year: {{ $year }}</h5>

        <!-- Total Disposed Milk -->
        <div class="mb-3">
            <strong>Total Disposed Milk:</strong> {{ number_format(array_sum($monthlyData), 2) }} Liters
        </div>

        <!-- Table View -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Month</th>
                    <th>Disposed Quantity (Liters)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyData as $month => $quantity)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ number_format($quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Line Chart -->
        <div class="chart-container" style="position: relative; height:300px; width:100%">
    <canvas id="disposeMilkChart"></canvas>
</div>
    @endif
</div>
@endsection

@section('js')
@if ($year)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('disposeMilkChart').getContext('2d');
        
        // Ensure data is properly formatted
        const labels = {!! json_encode(array_keys($monthlyData)) !!};
        const data = {!! json_encode(array_values($monthlyData)) !!};
        
        // Debugging: Log data to console
        console.log('Chart Data:', {
            labels: labels,
            data: data
        });

        try {
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Disposed Milk (Liters)',
                        data: data,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    });
</script>
@endif
@endsection
