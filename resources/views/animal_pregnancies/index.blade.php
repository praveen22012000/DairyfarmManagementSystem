@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals Pregnancies</h2>
                </div>
                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal_pregnancies.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Pregnancy</a>
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
                        <th>Pregnancy ID</th>
                        <th>Breeding ID</th>
                        <th>Femal Cow Name</th>
                        <th>Veterinarian</th>
                        <th>Confimation Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                  
                    @foreach($pregnancies as $pregnancie)
                    <tr>
                        <td>{{$pregnancie->id}}</td>
                        <td>{{$pregnancie->breeding_id}}</td>
                        <td>{{$pregnancie->AnimalDetail->animal_name}}</td>
                      
                        <td>{{$pregnancie->user->name}}</td>
                        <td>{{$pregnancie->confirmation_date}}</td>

                        <td>{{$pregnancie->pregnancy_status}}</td>

                       
                        <td>
                        <a href="{{route('animal_pregnancies.view',$pregnancie->id)}}" class="btn btn-info">View</a>
                    
                        <a href="{{route('animal_pregnancies.edit',$pregnancie->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $pregnancie->id }})">Delete</button>
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
   function confirmDelete(pregnancieId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the animal pregnancy record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/animal_pregnancies/${pregnancieId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection