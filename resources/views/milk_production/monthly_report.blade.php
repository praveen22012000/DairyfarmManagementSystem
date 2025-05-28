@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">

    <h2 class="mb-4">Milk Production Monthly Report {{$year}} </h2>

    <!-- Year dropdown -->
    <form method="GET" action="{{ route('milk_records_monthly.report') }}" class="mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @for ($y = now()->year; $y >= 2000; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>



     <!-- Chart -->
    <div style="height: 400px; width: 100%;">
        <canvas id="milkChart" height="400"></canvas>
    </div>

    <br>

    <!-- Table -->
     <h4>Monthly Milk Production Data ({{ $year }})</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach(array_keys($monthlyData) as $month)
                    <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($monthlyData as $quantity)
                    <td>{{ number_format($quantity, 2) }}L</td>
                @endforeach
            </tr>

            <tr>
                <td>Total Quantity</td>
                <td>{{ number_format(array_sum($monthlyData), 2) }}</td>
            </tr>
        </tbody>
    </table>

   

</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('milkChart').getContext('2d');
        
        // Ensure data is properly formatted
        const labels = {!! json_encode(array_keys($monthlyData)) !!};
        const data = {!! json_encode(array_values($monthlyData)) !!};
        
        console.log('Chart Data:', labels, data); // Debug output
        
        const milkChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Milk Production (Liters)',
                    data: data,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
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
    });
</script>
@endsection
