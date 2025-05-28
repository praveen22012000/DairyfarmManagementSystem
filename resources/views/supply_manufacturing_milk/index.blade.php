@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Milk Consumption For Manufacturing Products </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('milk_allocated_for_manufacturing.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Milk Consumption Record
                    </a>
               
                </div>

            </div>

             <!-- start-->
        <div class="card-header">
            
                <a class="btn btn-primary" href="{{ route('monthly_milk_allocation.report', ['year' => now()->year]) }}">
                     View Monthly Chart
                </a>   
        </div>

        <!--end -->

      
        <!--END -->
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
                        <th>Milk Production  </th>
                        <th>Consumed Date </th>
                        <th>Product Name</th>
                        <th>Consumed Quantity</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($productionSupplyDetails as $productionSupplyDetail)
                    <tr>
                      

                        <td>{{$productionSupplyDetail->id}}</td>
                        <td>{{$productionSupplyDetail->production_milk->AnimalDetail->animal_name.'|'.$productionSupplyDetail->production_milk->production_date.'|'.$productionSupplyDetail->production_milk->shift}}</td>
                        <td>{{$productionSupplyDetail->production_supply->date}}</td>
                        <td>{{$productionSupplyDetail->milk_product->product_name}}</td>
                        <td>{{ $productionSupplyDetail->consumed_quantity }}</td>
                        
                       
                 

                        <td>

                        <a href="{{route('milk_allocated_for_manufacturing.view',$productionSupplyDetail->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('milk_allocated_for_manufacturing.edit',$productionSupplyDetail->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="">Delete</button>
                    
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

@endsection


