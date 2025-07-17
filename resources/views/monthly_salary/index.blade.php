@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Salary Assignment Details</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('monthly_salary_assign.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Pay Salary
                    </a>
               
                    

                </div>

            </div>

                <!-- start-->
        <div class="card-header">
            
              
        </div>

        <!--end -->
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="salaryAssignmentTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Salary Month</th>
                        <th>Paid At</th>
                      
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($monthly_salary_assignments as $monthly_salary_assignment)
                    <tr>
                      

                        <td>{{$monthly_salary_assignment->id}}</td>
                        <td>{{$monthly_salary_assignment->user->name}}</td>
                        <td>{{$monthly_salary_assignment->amount_paid}}</td>
                        <td>{{$monthly_salary_assignment->salary_month}}</td>
                        <td>{{$monthly_salary_assignment->paid_at}}</td>
                        

                        <td>

                        <a href="{{ route('monthly_salary_assign.view',$monthly_salary_assignment->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('monthly_salary_assign.edit',$monthly_salary_assignment->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $monthly_salary_assignment->id }})">Delete</button>
                    
                        </td>
                    </tr>
                @endforeach
                <tbody>
            
                </tbody>
            </table>

            <form id="deleteForm" method="post" style="display:none;">
            @csrf
            @method('POST')
            </form>
            
            <div class="pt-2">
                <div class="float-right">
                   
            </div>
            </div>
        </div>
    </div>
</div>


</div>

@endsection




@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   function confirmDelete(monthlySalaryAssignmentId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the salary assignment record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/monthly_salary_details/${monthlySalaryAssignmentId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#salaryAssignmentTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For Supplier Records:"
        }
    });
});
</script>
@endsection


