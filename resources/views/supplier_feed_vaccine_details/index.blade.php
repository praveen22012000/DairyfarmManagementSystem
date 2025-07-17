@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Supplier Details </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('suppliers.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Supplier
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="supplierTable">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Name </th>
                        <th>Phone Number </th>
                        <th>Email</th>
                        <th>Address</th>
                     
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($suppliers as $supplier)
                    <tr>
                      

                        <td>{{$supplier->id}}</td>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->phone_no}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>{{$supplier->address}}</td>
                 
                 

                        <td>

                        <a href="{{ route('supply_feed_vaccine.view',$supplier->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('supply_feed_vaccine.edit',$supplier->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$supplier->id}})">Delete</button>
                    
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
   function confirmDelete(supplierId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the supplier record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/supplier_details/${supplierId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#supplierTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For Supplier Records:"
        }
    });
});
</script>


@endsection


