
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')
<div class="container">
    <h3>Monthly Feed Purchase Report - {{ $year }}</h3>

    <!-- Year Selector -->
    <form method="GET" action="{{ route('report.monthly_feed_purchase') }}" class="mb-4">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="form-control w-25 d-inline mx-2">
            @foreach ($years as $yr)
                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
        </select>
    </form>

     <h5>Year: {{ $year }}</h5>
        

    <!-- Table -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Month</th>
                @foreach ($feeds as $feed)
                    <th>{{ $feed }} (Qty)</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($tableData as $row)
                <tr>
                    <td>{{ $row['month'] }}</td>
                    @foreach ($feeds as $feed)
                        <td>{{ $row[$feed] }}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr class="font-weight-bold bg-light">
                <td>Total ({{ $year }})</td>
                    @foreach ($feeds as $feed)
                        <td>{{ $totalPerFeed[$feed] }}</td>
                    @endforeach
            </tr>

            

        </tbody>
    </table>


    <div class="chart-container" style="position: relative; height:300px; width:100%">
            <canvas id="feedChart"></canvas>
    </div>

</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('feedChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($tableData, 'month')) !!},
                datasets: [
                    @foreach ($feeds as $feed)
                    {
                        label: '{{ $feed }}',
                        data: {!! json_encode(array_column($tableData, $feed)) !!},
                        backgroundColor: '{{ sprintf('rgba(%d, %d, %d, 0.6)', rand(50,255), rand(50,255), rand(50,255)) }}',
                        borderColor: '{{ sprintf('rgba(%d, %d, %d, 1)', rand(50,255), rand(50,255), rand(50,255)) }}',
                        borderWidth: 1
                    },
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Feed Purchase Quantity (in Units)'
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
                            text: 'Quantity'
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
