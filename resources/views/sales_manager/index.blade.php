@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Sales Managers</h2>
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
            <table class="table" id="salesManagerTable">
                <thead class="thead-dark">
                    <tr>

                        <th> ID</th>
                        <th>Name</th>
                        <th>Phone number</th>
                        <th>Address</th>
                      
                        <th>Experience</th>
                       

                        <th>Actions</th>
                       
                      
                    </tr>
               
                </thead>
                  
                <tbody>
                      @foreach($sales_managers as $sales_manager)

                    <tr>
                      
                        <td>{{$sales_manager->id}}</td>
                        <td>{{$sales_manager->user->name}}</td>
                        <td>{{$sales_manager->user->phone_number}}</td>
                        <td>{{$sales_manager->user->address}}</td>
                     
                        <td>{{$sales_manager->experience}}</td>
                   
                        <td>

                        <a href="{{ route('sales_manager.view',$sales_manager->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('sales_manager.edit',$sales_manager->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$sales_manager->id}})">Delete</button>

                        </td>
                    </tr>
                    @endforeach
                
            
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
   function confirmDelete(salesManagerId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the sales manager record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/users/sales_manager_list/${salesManagerId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#salesManagerTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Sales Manager Records:"
        }
    });
});
</script>

@endsection


