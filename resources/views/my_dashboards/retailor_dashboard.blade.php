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
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Retailors
                                              </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $no_of_retailors }}</div>
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
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders Monthly
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_no_of_orders }}</div>
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
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Cancel Orders Monthly
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_no_of_order_canceled }}</div>
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
                                              Total Rejected Orders</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_no_of_order_rejected }}</div>

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
                                            UnPaid Orders (Monthly)</div>
                                               
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{  $total_unpaid_orders }}</div>
                                               
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
                                               Total Delivered Orders</div>
                                           
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $total_delivered_orders }}</div>
                                                 
                                                   
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
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Products Received
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                  

                                            @foreach($total_orders as $tot)
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tot->product_name }}-{{ $tot->total_amount_of_products }}</div>
                                            @endforeach
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
                                            Total Amount Spend Monthly</div>

                                           @foreach($total_amount as $tot)
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tot->product_name }} - {{ $tot->total_amount_spent }} </div>
                                            @endforeach
                                            
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
                                    <h6 class="m-0 font-weight-bold text-primary">Monthly Expenses For Milk Products</h6>
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
<style>
    .chart-area {
        height: 400px;
        width: 100%;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the months and values from Laravel
        const months = {!! json_encode(array_keys($monthlyData)) !!};
        const milkData = {!! json_encode(array_values($monthlyData)) !!};
        
        console.log('Months:', months);
        console.log('Milk Data:', milkData);

        const ctx = document.getElementById('milkSalesChart').getContext('2d');

        const milkChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Cost (Rs.)',
                    data: milkData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount(Rs.)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>


@endsection