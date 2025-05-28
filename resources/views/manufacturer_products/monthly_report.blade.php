@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Monthly Manufacturing Report for {{ $year }}</h2>

    <!-- Year Filter -->
    <form method="GET" action="{{ route('milk_products.report') }}" class="mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            @for ($i = now()->year; $i >= 2023; $i--)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </form>

    <!-- Chart -->
    <canvas id="milkChart" height="100"></canvas>

    <!-- Table -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Product</th>
                @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $month)
                    <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($productsByMonth as $product => $monthlyData)
                <tr>
                    <td>{{ $product }}</td>
                    @foreach ($monthlyData as $month => $quantity)
                        <td>{{ $quantity }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Canvas element verification
    const canvas = document.getElementById('milkChart');
    if (!canvas) {
        console.error('Canvas element not found');
        return;
    }

    // 2. Chart.js library check
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded');
        return;
    }

    try {
        // 3. Get and verify chart data
        const chartData = @json($productsByMonth);
        const months = ['January','February','March','April','May','June',
                      'July','August','September','October','November','December'];

        // 4. Handle empty data case
        if (!chartData || Object.keys(chartData).length === 0) {
            canvas.style.display = 'none';
            const noDataMsg = document.createElement('div');
            noDataMsg.className = 'alert alert-info my-4';
            noDataMsg.textContent = 'No manufacturing data available for {{ $year }}';
            canvas.parentNode.insertBefore(noDataMsg, canvas);
            return;
        }

        // 5. Prepare datasets
        const datasets = Object.entries(chartData).map(([product, values], idx) => {
            const hue = (idx * 60) % 360;
            return {
                label: product,
                data: months.map(month => values[month] || 0),
                backgroundColor: `hsla(${hue}, 70%, 60%, 0.7)`,
                borderColor: `hsl(${hue}, 70%, 50%)`,
                borderWidth: 1,
                barThickness: 'flex', // Makes bars adjust automatically
                maxBarThickness: 30,  // Maximum bar thickness
                minBarLength: 2       // Minimum bar length
            };
        });

        // 6. Get canvas context
        const ctx = canvas.getContext('2d');
        if (!ctx) {
            throw new Error('Could not get 2D context');
        }

        // 7. Destroy previous chart if exists
        if (canvas.chart) {
            canvas.chart.destroy();
        }

        // 8. Create new chart with proper height control
        canvas.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months.map(month => month.substring(0, 3)),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Crucial for height control
                indexAxis: 'x', // Horizontal bars (change to 'y' for vertical)
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Monthly Milk Product Manufacturing ({{ $year }})',
                        padding: {
                            top: 10,
                            bottom: 15
                        },
                        font: {
                            size: 14
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 6,
                            padding: 5,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        },
                        grid: {
                            drawBorder: true
                        },
                        title: {
                            display: true,
                            text: 'Quantity',
                            padding: {
                                top: 5,
                                bottom: 5
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 5,
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 45
                        },
                        title: {
                            display: true,
                            text: 'Month',
                            padding: {
                                top: 5,
                                bottom: 5
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });

        // 9. Optional: Adjust container height based on data
        const idealHeight = 300 + (Object.keys(chartData).length * 10);
        canvas.parentNode.style.height = `${Math.min(500, idealHeight)}px`;

    } catch (error) {
        console.error('Chart error:', error);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = 'Error displaying chart data';
        canvas.parentNode.insertBefore(errorDiv, canvas);
    }
});
</script>
@endsection
