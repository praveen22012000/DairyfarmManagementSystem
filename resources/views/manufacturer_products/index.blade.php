@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Manufacturing Milk Products </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('manufacture_product.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Manufacture Record
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Milk Product </th>
                        <th>Quantity</th>
                        <th>Stock Quantity</th>
                        <th>Manufacture Date </th>
                        <th>Expire Date</th>
                        <th>Manufacurred By</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($manufacturerProducts as $manufacturerProduct)
                    <tr>
                      

                        <td>{{$manufacturerProduct->id}}</td>
                        <td>{{$manufacturerProduct->milk_product->product_name}}</td>
                        <td>{{$manufacturerProduct->quantity}}</td>
                        <td>{{$manufacturerProduct->stock_quantity}}</td>
                        <td>{{$manufacturerProduct->manufacture_date}}</td>
                        <td>{{$manufacturerProduct->manufacture_date}}</td>
                        <td>{{$manufacturerProduct->user->name}}</td>
                       
                 

                        <td>

                        <a href="{{route('manufacture_product.view',$manufacturerProduct->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('manufacture_product.edit',$manufacturerProduct->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $manufacturerProduct->id }})">Delete</button>
                    
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
   function confirmDelete(manufacturerProductId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Manufacture milk Product record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/milk_products_manufacture_details/${manufacturerProductId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


