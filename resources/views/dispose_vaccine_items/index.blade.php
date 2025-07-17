@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Dispose Vaccine Items  </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('dispose_vaccine_items.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Dispose Record
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="disposeVaccineTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th> Vaccine </th>
                        <th>Purchase Date</th>
                        <th>Manufacture Date</th>
                        <th>Expire Date</th>
                        <th> Dispose Quantity </th>
                        <th> Reason</th>
                 
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($disposeVaccineItems as $disposeVaccineItem)
                    <tr>
                      

                        <td>{{$disposeVaccineItem->id}}</td>
                        <td>{{$disposeVaccineItem->purchase_vaccine_items->vaccine->vaccine_name}}</td>
                        <td>{{$disposeVaccineItem->purchase_vaccine_items->purchase_vaccine->purchase_date}}</td>
                        <td>{{$disposeVaccineItem->purchase_vaccine_items->manufacture_date}}</td>
                        <td>{{$disposeVaccineItem->purchase_vaccine_items->expire_date}}</td>
                        <td>{{$disposeVaccineItem->dispose_quantity}}</td>
                        <td>{{$disposeVaccineItem->reason_for_dispose}}</td>
                        
                        
                       
                 

                        <td>

                        <a href="{{ route('dispose_vaccine_items.view',$disposeVaccineItem->id)}}" class="btn btn-info">View</a>
                        <a href="{{ route('dispose_vaccine_items.edit',$disposeVaccineItem->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $disposeVaccineItem->id }})">Delete</button>
                    
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
   function confirmDelete(disposeVaccineItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Dispose Vaccine Item record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/dispose_vaccine_items/${disposeVaccineItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#disposeVaccineTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Dispose Feed Items records:"
        }
    });
});
</script>

@endsection


