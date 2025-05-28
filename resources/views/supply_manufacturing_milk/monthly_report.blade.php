
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h3>Monthly Milk Consumption Report</h3>

    <!-- Year Selection -->
    <form method="GET" action="{{ route('monthly_milk_allocation.report') }}" class="form-inline mb-4">
        <label for="year" class="mr-2">Select Year:</label>
        <select name="year" id="year" class="form-control mr-2">
            <option value="">-- Choose Year --</option>
            @foreach ($years as $yr)
                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">View</button>
    </form>

    @if ($year)
        <h5>Year: {{ $year }}</h5>
        <div class="mb-3">
            <strong>Total Consumption:</strong> {{ number_format(array_sum($monthlyConsumption), 2) }} Liters
        </div>

        <!-- Table View -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Month</th>
                    <th>Consumed Quantity (Liters)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyConsumption as $month => $quantity)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ number_format($quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

      
        <div class="chart-container" style="position: relative; height:300px; width:100%">
    <canvas id="consumptionChart"></canvas>
</div>
    @endif
</div>
@endsection

@section('js')
@if ($year)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('consumptionChart');
        
        // Debug: Check if data exists
        console.log('Chart Data:', {
            labels: {!! json_encode(array_keys($monthlyConsumption)) !!},
            data: {!! json_encode(array_values($monthlyConsumption)) !!}
        });

        try {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($monthlyConsumption)) !!},
                    datasets: [{
                        label: 'Milk Consumption (Liters)',
                        data: {!! json_encode(array_values($monthlyConsumption)) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,  // Important for container sizing
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
            console.error('Chart Error:', error);
        }
    });
</script>
@endif
@endsection
