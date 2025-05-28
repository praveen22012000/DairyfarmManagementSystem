@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h2 class="mb-4" style="text-align:center">Milk Production Report (By Animal & Year)</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('milk.animal_year_chart') }}" class="mb-4 row">
        <div class="col-md-3">
            <label for="year">Select Year:</label>
            <select name="year" id="year" class="form-control" required>
                <option value="">-- Select Year --</option>
                @for ($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}" {{ ($year == $i) ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-4">
            <label for="animal_id">Select Animal:</label>
            <select name="animal_id" id="animal_id" class="form-control" required>
                <option value="">-- Select Animal --</option>
                @foreach ($animals as $animal)
                    <option value="{{ $animal->id }}" {{ ($animal_id == $animal->id) ? 'selected' : '' }}>
                        {{ $animal->animal_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">View Chart</button>
        </div>
    </form>


    <!-- Chart Section -->
    @if (!empty($monthlyProduction))
    <!-- Table View -->
    <div class="mt-4">
        <h4>Milk Production Summary for Selected Animal in {{ $year }}</h4>
      
        <table class="table table-bordered mt-3">
            <thead class="thead-dark">
                
                <tr>
                    <th>Month</th>
                     @foreach(array_keys($monthlyProduction) as $month)
                    <th>{{ $month }}</th>
                    @endforeach
                   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Quantity</td>
                  @foreach($monthlyProduction as $quantity)
                    <td>{{ number_format($quantity, 2) }}L</td>
                @endforeach

                   

                </tr>

                <tr>
                <td>Total Quantity</td>
                <td>{{ number_format(array_sum($monthlyProduction), 2) }}</td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Line Chart View -->
    <div class="mt-5" style="height: 500px;">  <!-- Increased from 400px to 500px -->
            <canvas id="animalMilkChart"></canvas>
    </div>
@endif

</div>
@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if (!empty($monthlyProduction))
        console.log('Chart Data:', {!! json_encode($monthlyProduction) !!});
        
        const ctx = document.getElementById('animalMilkChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Object.keys({!! json_encode($monthlyProduction) !!}),
                    datasets: [{
                        label: 'Milk Production (Liters) for {{ $animal->animal_name ?? "Selected Animal" }}',
                        data: Object.values({!! json_encode($monthlyProduction) !!}),
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Milk Production - {{ $year }}',
                            font: { size: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(2) + ' liters';
                                }
                            }
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
        } else {
            console.error('Canvas element not found!');
        }
    @else
        console.log('No production data available to render chart');
    @endif
});
</script>
@endsection
