
@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Dispose Milk Products </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('dispose_milk_product.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Dispose Milk Products
                    </a>
               
                </div>

            </div>

            
                <!-- start-->
        <div class="card-header">
            
                <a class="btn btn-primary" href="{{ route('dispose_milk_products_monthly.report') }}">
                     View Monthly Chart
                </a>   

               
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
                        <th>Milk Production Item  </th>
                        <th>Disposed By </th>
                        <th>Date</th>
                        <th>Dispose Amount</th>
                        <th>Reason for Dispose</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($disposeMilkProducts as $disposeMilkProduct)
                    <tr>
                      

                        <td>{{$disposeMilkProduct->id}}</td>
                        <td>{{$disposeMilkProduct->manufacture_proudct->manufacture_date.'|'.$disposeMilkProduct->manufacture_proudct->milk_product->product_name}}</td>
                        <td>{{$disposeMilkProduct->user->name}}</td>
                        <td>{{$disposeMilkProduct->date}}</td>
                        <td>{{$disposeMilkProduct->dispose_quantity}}</th>
                        <td>{{$disposeMilkProduct->reason_for_dispose}}</td>
                
                        <td>

                        <a href="{{route('dispose_milk_product.view',$disposeMilkProduct->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('dispose_milk_product.edit',$disposeMilkProduct->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$disposeMilkProduct->id}})">Delete</button>
                    
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
   function confirmDelete(disposeMilkProductsId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Dipose milk Product record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/dispose_milk_products/${disposeMilkProductsId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


