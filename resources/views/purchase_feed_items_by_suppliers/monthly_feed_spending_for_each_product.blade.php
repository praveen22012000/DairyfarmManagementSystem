
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h2 class="mb-4">Monthly Feed Purchase Spending Report - {{ $year }}</h2>

    <form method="GET" action="{{ route('reports.feed_spending_for_each_product') }}" class="mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="form-control w-25 d-inline mx-2">
            @foreach($years as $yr)
                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
        </select>
    </form>

    

    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Month</th>
                <th>Total Amount Spent (in currency)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableData as $data)
                <tr>
                    <td>{{ $data['month'] }}</td>
                    <td>{{ number_format($data['amount_spent'], 2) }}</td>
                </tr>
            @endforeach

            <tr style="font-weight: bold; background-color: #f8f9fa;">
            <td>Total for {{ $year }}</td>
            <td>
                {{ number_format(collect($tableData)->sum('amount_spent'), 2) }}
            </td>
        </tr>

        </tbody>
       
    </table>

    <div class="chart-container" style="position: relative; height:300px; width:100%">
            <canvas id="spendingChart"></canvas>
    </div>
    
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('spendingChart').getContext('2d');
        
        // Verify data is available before creating chart
        const labels = {!! json_encode(array_column($tableData, 'month')) !!};
        const chartData = {!! json_encode($monthlyCost) !!};
        
        console.log('Chart Labels:', labels); // Debug output
        console.log('Chart Data:', chartData); // Debug output

        if (labels.length && chartData.length) {
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Amount Spent on Feed (per month)',
                        data: chartData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Feed Spending'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rs.' + context.raw.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rs.' + value;
                                }
                            },
                            title: {
                                display: true,
                                text: 'Amount Spent (Rs.)'
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
            console.error('No data available for chart');
            // Optionally display a message to the user
            document.getElementById('spendingChart').parentElement.innerHTML = 
                '<div class="alert alert-info">No spending data available for the selected year.</div>';
        }
    });
</script>
@endsection
