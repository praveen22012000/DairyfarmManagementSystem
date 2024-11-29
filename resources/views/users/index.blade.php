@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')

<!-- Begin Page Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Users</h1>
            </div>

            <a  class="btn btn-success btn-md btn-rounded" href="{{route('users.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add User</a>

            <div class="row">
                <!-- Admin Card -->
                <div class="col-md-4 col-sm-12 mb-4"> 
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://its.gmu.edu/wp-content/uploads/user-blue.png" alt="Admin" class="img-fluid rounded-square" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get admin count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link admin show blade  -->
                            <p class="card-text"> <a href=""> Farm Owner </a> </p>
                        </div>
                    </div>
                </div>

                <!-- Branch Staff Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://www.clipartmax.com/png/middle/333-3330554_hospital-staff-icon-clipart-clinic-computer-icons-medicine-icon.png" alt="Branch Staff" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get branchstaff count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link branchstaff show blade  -->
                            <p class="card-text"> <a href=""> Veterinarian </a> </p>
                        </div>
                    </div>
                </div>

                <!-- Instructor Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/008/854/358/small/karate-illustration-modern-design-file-png.png" alt="Instructor" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get instructor count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link instructor show blade  -->
                            <p class="card-text"> <a href=""> Instructor </a></p>
                        </div>
                    </div>
                </div>

                <!-- Student Card -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpCwLhhcN3ZHoPpop_C26TcefHSQkW_-TUKAP77wpUCXYR8iegKqu2t66JF1ErH5_wXg0&usqp=CAU" alt="Student" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <!-- get student count dynamically -->
                            <h5 class="card-title mt-3"></h5>
                            <!-- link student show blade  -->
                            <p class="card-text"> <a href="#"> Student </a></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End of Main Content -->

@endsection