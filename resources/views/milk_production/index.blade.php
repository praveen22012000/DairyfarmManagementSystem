@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Milk Productions Details</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('production_milk.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add Milk Record
                    </a>
               
                    

                </div>

            </div>

                <!-- start-->
        <div class="card-header">
            
                

              
               
        </div>

        <!--end -->
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="milkProductionTable">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Female Cow Name</th>
                        <th>Production Date</th>
                        <th>Shift</th>
                        <th>Quantity Liters</th>
                        <th>Stock Quantity</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($production_milk_details as $production_milk_detail)
                    <tr>
                      

                        <td>{{$production_milk_detail->id}}</td>
                        <td>{{$production_milk_detail->AnimalDetail->animal_name}}</td>
                        <td>{{$production_milk_detail->production_date}}</td>
                        <td>{{$production_milk_detail->shift}}</td>
                        <td>{{$production_milk_detail->Quantity_Liters}}</td>
                        <td>{{$production_milk_detail->stock_quantity}}</td>

                        <td>

                        <a href="{{ route('production_milk.view',$production_milk_detail->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('production_milk.edit',$production_milk_detail->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $production_milk_detail->id }})">Delete</button>
                    
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
   function confirmDelete(milkProductionDetailId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the milk Production record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/milk_production_details/${milkProductionDetailId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>


<script>
$(document).ready(function() {
    $('#milkProductionTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Breeding Events:"
        }
    });
});
</script>

@endsection


