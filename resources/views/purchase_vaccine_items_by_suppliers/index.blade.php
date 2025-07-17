@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Purchase Vaccine Items </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('purchase_vaccine_items.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Purchase Vaccine Item
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="purchaseVaccineTable">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Supplier</th>
                        <th>Vaccine Name </th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Manufacture Date</th>
                        <th>Expire Date</th>
                       
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($purchaseVaccineItems as $purchaseVaccineItem)
                    <tr>
                      

                        <td>{{$purchaseVaccineItem->id}}</td>
                        <td>{{$purchaseVaccineItem->purchase_vaccine->supplier->name}}</td>
                        <td>{{$purchaseVaccineItem->vaccine->vaccine_name}}</td>
                        <td>{{$purchaseVaccineItem->unit_price}}</td>
                        <td>{{$purchaseVaccineItem->purchase_quantity}}</td>
                        <td>{{$purchaseVaccineItem->manufacture_date}}</td>
                        <td>{{$purchaseVaccineItem->expire_date}}</td>
                 

                        <td>

                        <a href="{{ route('purchase_vaccine_items.view',$purchaseVaccineItem->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('purchase_vaccine_items.edit',$purchaseVaccineItem->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $purchaseVaccineItem->id }})">Delete</button>
                    
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
   function confirmDelete(purchaseVaccineItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Vaccine Purchase record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/purchase_vaccine_items/${purchaseVaccineItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#purchaseVaccineTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Dispose Feed Items records:"
        }
    });
});
</script>

@endsection


