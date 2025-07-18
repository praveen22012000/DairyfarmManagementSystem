@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Veterinarians</h2>
                </div>

                <div class="float-right">
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="veterinariansTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Name</th>
                        <th>Phone number</th>
                        <th>Address</th>
                        <th>Experience</th>
                        <th>Age</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                    <tr>
                        @foreach($veterinarians as $veterinarian)

                        <td>{{$veterinarian->id}}</td>
                        <td>{{$veterinarian->user->name}}</td>
                        <td>{{$veterinarian->user->phone_number}}</td>
                        <td>{{$veterinarian->user->address}}</td>
                        <td>{{$veterinarian->experience}}</td>
                        <td>{{$veterinarian->age}}</td>

                        <td>

                        <a href="{{ route('veterinarians.view',$veterinarian->id) }}" class="btn btn-info">View</a>
                        <a href="{{route('veterinarians.edit',$veterinarian->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $veterinarian->id }})">Delete</button>
                    
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
   function confirmDelete(veterinarianId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the veterinarian record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/users/veterinarian_list/${veterinarianId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('#veterinariansTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search Veterinarian records:"
        }
    });
});
</script>

@endsection


