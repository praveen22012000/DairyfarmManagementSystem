@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')

<!-- Begin Page Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            
        <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Users</h1>
                <a class="btn btn-success btn-md btn-rounded" href="{{ route('users.create') }}">
                <i class="mdi mdi-plus-circle mdi-18px"></i> Add User</a>
                
        </div>


            <div class="row">
                <!--Farm Owner Admin Card -->
                <div class="col-md-4 col-sm-12 mb-4"> 
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://its.gmu.edu/wp-content/uploads/user-blue.png" alt="Admin" class="img-fluid rounded-square" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get farm Owner count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link admin show blade  -->
                            <p class="card-text"> <a href=""> Farm Owner </a> </p>
                        </div>
                    </div>
                </div>

                <!-- Veterinarians Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="" alt="Veterinarians" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get veterinarian count dynamically -->
                            <h5 class="card-title mt-3">{{$numberOfVeterinarians}}</h5>
                            <!-- link veterinarian show blade  -->
                            <p class="card-text"> <a href="{{ route('veterinarians.list') }}"> Veterinarian </a> </p>
                        </div>
                    </div>
                </div>

                <!-- Retailer Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/008/854/358/small/karate-illustration-modern-design-file-png.png" alt="Retailer" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get  count dynamically -->
                            <h5 class="card-title mt-3">{{$numberOfRetailers}}</h5>
                            <!-- link retailer show blade  -->
                            <p class="card-text"> <a href="{{ route('retailers.list') }}"> Retailer </a></p>
                        </div>
                    </div>
                </div>

                <!-- Supplier Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpCwLhhcN3ZHoPpop_C26TcefHSQkW_-TUKAP77wpUCXYR8iegKqu2t66JF1ErH5_wXg0&usqp=CAU" alt="Student" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get student count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link student show blade  -->
                            <p class="card-text"> <a href="#"> supplier </a></p>
                        </div>
                    </div>
                </div>

                <!-- General Manager Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpCwLhhcN3ZHoPpop_C26TcefHSQkW_-TUKAP77wpUCXYR8iegKqu2t66JF1ErH5_wXg0&usqp=CAU" alt="Student" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get student count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link student show blade  -->
                            <p class="card-text"> <a href="#"> General Manager </a></p>
                        </div>
                    </div>
                </div>

                 <!-- General Manager Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpCwLhhcN3ZHoPpop_C26TcefHSQkW_-TUKAP77wpUCXYR8iegKqu2t66JF1ErH5_wXg0&usqp=CAU" alt="Student" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get student count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link student show blade  -->
                            <p class="card-text"> <a href="#"> Sales Manager </a></p>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
<!-- End of Main Content -->

@endsection