@extends('layouts.admin.master')
@section('content')
     
@section('content')

     <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                      <!--  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Animals</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{  $totalAnimals }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-cow fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                               Milk Stock Quantity</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMilkInStock  }} Liters</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-glass-water fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Milk
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$todayMilk}}Liters</div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-glass-water fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Feed Inventory (KG)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Grain  {{$total_grain_Feed}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Wheat  {{$total_wheat_Feed}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                     <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                NO OF ANIMALS BORN IN THIS WEEK</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{  $weeklyAnimals }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fas fa-paw fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                               WEEKLY RETAILOR ORDERS</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $weeklyRetailorOrders }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Milk
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$todayMilk}}Liters</div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-glass-water fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                MILK PRODUCT STOCK</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Yogurt  {{$total_yogurt_stock}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Cheese  {{$total_cheese_stock}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

               <div class="row">
                                <!-- Milk Product Sales Chart -->
                        <div class="col-12"> <!-- Changed from col-xl-8 col-lg-7 to col-12 -->
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Monthly Milk Product Sales</h6>
                                </div>

                        <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="milkSalesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                   
                </div>
                <!-- /.container-fluid -->


            
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('milkSalesChart');
        
        if (ctx) {
            // Color palette
            const titleColor = '#5a5c69';  // Dark gray for titles
            const tickColor = '#4e73df';  // SB Admin primary blue for ticks
            const gridColor = 'rgba(78, 115, 223, 0.1)';  // Light blue grid
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total Units Sold',
                        data: @json($sales),
                        backgroundColor: 'rgba(78, 115, 223, 0.6)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(78, 115, 223, 0.8)',
                        hoverBorderColor: 'rgba(78, 115, 223, 1)'
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
                                text: 'Units Sold',
                                color: titleColor,
                                font: {
                                    weight: 'bold',
                                    size: 12
                                }
                            },
                            ticks: {
                                color: tickColor,
                                font: {
                                    weight: '500'
                                },
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : value.toFixed(1);
                                }
                            },
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month',
                                color: titleColor,
                                font: {
                                    weight: 'bold',
                                    size: 12
                                }
                            },
                            ticks: {
                                color: function(context) {
                                    // Different color for each month label
                                    const monthColors = [
                                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                        '#9966FF', '#FF9F40', '#8AC24A', '#F7464A',
                                        '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'
                                    ];
                                    return monthColors[context.index];
                                },
                                font: {
                                    weight: '600'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: titleColor,
                                font: {
                                    weight: '500'
                                },
                                boxWidth: 12,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 4
                        }
                    }
                }
            });
        }
    });
</script>
@endsection