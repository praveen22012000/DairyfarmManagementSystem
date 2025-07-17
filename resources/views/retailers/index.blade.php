@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Retailers</h2>
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
            <table id="retailorsTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Name</th>
                        <th>Phone number</th>
                        <th>Address</th>
                        <th>Store_name</th>
                  
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach($retailers as $retailer)
                    <tr>
                        

                        <td>{{$retailer->id}}</td>
                        <td>{{$retailer->user->name}}</td>
                        <td>{{$retailer->user->phone_number}}</td>
                        <td>{{$retailer->user->address}}</td>
                        <td>{{$retailer->store_name}}</td>
                    
                        <td>

                        <a href="{{route('retailers.view',$retailer->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('retailers.edit',$retailer->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $retailer->id }})">Delete</button>

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
   function confirmDelete(retailerId) {
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
                deleteForm.action = `/users/retailer_list/${retailerId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#retailorsTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Retailors Records:"
        }
    });
});
</script>


@endsection


