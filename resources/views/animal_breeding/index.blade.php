@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals Breedings</h2>
                </div>
                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal_breedings.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Breeding Events</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table" id="breedingTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Breeding ID</th>
                        <th>Female Cow Name</th>
                        <th>Male Name</th>
                        <th>Veterinarian</th>
                        <th>Breeding Date</th>
                        
                        <th>Breeding Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    @foreach($breedings as $breeding)
                    <tr>
                        <td>{{$breeding->id}}</td>
                        <td>{{$breeding->femalecow->animal_name}}</td>
                        <td>{{$breeding->malecow->animal_name}}</td>
                        <td>{{$breeding->user->name}}</td>
                        <td>{{$breeding->breeding_date}}</td>
                     
                        <td>{{$breeding->notes}}</td>

                        <td>
                        <a href="{{ route('animal_breedings.view',$breeding->id)}}" class="btn btn-info">View</a>
                    
                        <a href="{{ route('animal_breedings.edit',$breeding->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $breeding->id }})">Delete</button>
                        
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
   function confirmDelete(breedingId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the animal breedings record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/animal_breedings/${breedingId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#breedingTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Breeding Events:"
        }
    });
});
</script>


@endsection