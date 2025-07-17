@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Designation Salary</h2>
                </div>

                <div class="float-right">

                
                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('role_salary.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Assign Salary
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table  class="table">
                <thead class="thead-dark">
                    <tr>

                        <th> ID</th>
                        <th>Role</th>
                        <th>Salary</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                    <tr>
                        @foreach($role_salary as $role_sal)

                       
                        <td>{{ $role_sal->id }}</td>
                        <td>{{$role_sal->role->role_name}}</td>

                        <td>{{ $role_sal->salary }}</td>
                   
                        <td>

                        <a href="{{ route('role_salalry.view',$role_sal->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('role_salary.edit',$role_sal->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $role_sal->id }})">Delete</button>

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
   function confirmDelete(roleSalID) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the retailer record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/role_salary_details/${roleSalID}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#roleSalaryTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For Salary Records:"
        }
    });
});
</script>


@endsection


