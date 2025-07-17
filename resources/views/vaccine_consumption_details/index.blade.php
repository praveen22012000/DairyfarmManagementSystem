@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2> Vaccine Consumption Details </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('vaccine_consume_items.create')}}">
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
            <table id="vaccineConsuumptionTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Veterinarian</th>
                        <th>Animal</th>
                        <th>Vaccine</th>
                        <th>Vaccination Date</th>
                        <th>Quantity</th>
                    
                        <th>Actions</th>
                      
                    </tr>
                </thead>
              
                @foreach($vaccineConsumeItems as $vaccineConsumeItem)
                    <tr>
                      

                        <td>{{ $vaccineConsumeItem->id  }}</td>
                        <td>{{ $vaccineConsumeItem->vaccine_consume_detail->appointment->user->name }}</td>
                        <td>{{ $vaccineConsumeItem->animal->animal_name }}</td>
                        <td>{{ $vaccineConsumeItem->purchase_vaccine_items->vaccine->vaccine_name }}</td>
                        <td>{{ $vaccineConsumeItem->vaccine_consume_detail->vaccination_date}}</td>
                        <td>{{ $vaccineConsumeItem->consumed_quantity }}</td>
                        
                       
                 

                        <td>

                        <a href="{{ route('vaccine_consume_items.view',$vaccineConsumeItem->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('vaccine_consume_items.edit',$vaccineConsumeItem->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$vaccineConsumeItem->id}})">Delete</button>
                    
                        </td>
                        @endforeach
                    </tr>
                   
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
   function confirmDelete(vaccineConsumeItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Vaccine Consumption record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/vaccine_consume_items_by_animals/${vaccineConsumeItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#vaccineConsuumptionTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Dispose Feed Items records:"
        }
    });
});
</script>

@endsection


