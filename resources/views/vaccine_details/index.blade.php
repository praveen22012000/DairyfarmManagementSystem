@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2> Vaccine Details </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('vaccine.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Vaccine Record
                    </a>
               
                </div>

            </div>
          
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="vaccineTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Vaccine </th>
                        <th>Manufacturer</th>
                        
                        <th>Unit Type</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                @foreach($vaccines as $vaccine)
              
                    <tr>
                      

                        <td>{{$vaccine->id}}</td>
                        <td>{{$vaccine->vaccine_name}}</td>
                        <td>{{$vaccine->manufacturer}}</td>
                    
                        <td>{{$vaccine->unit_type}}</td>
                        <td>{{$vaccine->unit_price}}</td>
                       
                 

                        <td>

                        <a href="{{route('vaccine.view',$vaccine->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('vaccine.edit',$vaccine->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$vaccine->id}})">Delete</button>
                    
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
   function confirmDelete(vaccineId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Vaccine record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/vaccine_details/${vaccineId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#vaccineTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Dispose Feed Items records:"
        }
    });
});
</script>

@endsection


