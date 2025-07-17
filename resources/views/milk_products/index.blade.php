@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Milk Products Details</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('milk_product.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Milk Product
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="milkProductsTable">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Product Name</th>
                        <th>Unit Price(Rs.)</th>
                        <th>Ingredients</th>
                     
                      
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($milkProducts as $milkProduct)
                    <tr>
                      

                        <td>{{$milkProduct->id}}</td>
                        <td>{{$milkProduct->product_name}}</td>
                        <td>{{$milkProduct->unit_price}}</td>
                        <td>{{ $milkProduct->ingredients->pluck('ingredients')->implode(', ') }}</td>
                        
                       
                 

                        <td>

                        <a href="{{ route('milk_product.view',$milkProduct->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('milk_product.edit',$milkProduct->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $milkProduct->id }})">Delete</button>
                    
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
   function confirmDelete(milkProductId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the milk Product record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/milk_product_details/${milkProductId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#milkProductsTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Milk Products:"
        }
    });
});
</script>

@endsection


