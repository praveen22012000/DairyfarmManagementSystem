@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Users</h2>
                </div>

                 <div class="float-right">
                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('main_user_details.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add User
                    </a>
                </div>

                <div class="float-right">
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="mainUserTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                    <tr>
                        @foreach($users as $user)

                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->role->role_name}}</td>
                      
                        <td>

                        <a href="{{ route('main_user_details.view',$user->id) }}" class="btn btn-info">View</a>
                         @if ($user->role_id !== 1)
                        <a href="{{ route('main_user_details.edit',$user->id) }}" class="btn btn-primary">Edit</a>
                         @endif
                         @if ($user->role_id !== 1)
                        <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete</button>
                        @endif
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
   function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the user record from the system.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/users_main_details/${userId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#mainUserTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For User Records:"
        }
    });
});
</script>

@endsection


