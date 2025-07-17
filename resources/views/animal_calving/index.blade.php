@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals Calvings</h2>
                </div>
                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal_calving.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Calving Events</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="calvingTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Calving ID</th>
                        <th>Calf Name</th>
                        <th>Calving Date</th>
                        <th>Calving Notes</th>
                 
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                    @foreach($animal_calvings_details as $animal_calvings_detail)
                    <tr>
                        <td>{{$animal_calvings_detail->id}}</td>
                        <td>{{$animal_calvings_detail->calf->animal_name}}</td>
                      
                        <td>{{$animal_calvings_detail->calving_date}}</td>
                        <td>{{$animal_calvings_detail->calving_notes}}</td>

            

                        <td>
                        <a href="{{route('animal_calvings.view',$animal_calvings_detail->id)}}" class="btn btn-info">View</a>
                    
                        <a href="{{route('animal_calvings.edit',$animal_calvings_detail->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $animal_calvings_detail->id }})">Delete</button>
                        
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
   function confirmDelete(animalCalvingDetailId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the animal calvings record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/animal_calvings/${animalCalvingDetailId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>


<script>
$(document).ready(function() {
    $('#calvingTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search calvings Events:"
        }
    });
});
</script>


@endsection