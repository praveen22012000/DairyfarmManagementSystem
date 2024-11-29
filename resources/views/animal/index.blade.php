@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals</h2>
                </div>

                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Animal</a>
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
                        <th>Animal Name</th>
                        <th>Animal Type</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      
                    </tr>
                </thead>
                    @foreach($animals as $animal)
                    <tr>
                        <td>{{$animal->id}}</td>
                        <td>{{$animal->animal_name}}</td>
                        <td>{{$animal->AnimalType->animal_type}}</td>
                        <td><a href="{{route('animal.view',$animal->id)}}" class="btn btn-info">View</a></td>
                        <td><a href="{{route('animal.edit',$animal->id)}}" class="btn btn-primary">Edit</a></td>
                     
                        <td><button class="btn btn-danger" onclick="confirmDelete({{ $animal->id }})">Delete</button></td>
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
   function confirmDelete(animalId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the animal record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/animal/${animalId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="animal_id" id="animal-id">
</form>
