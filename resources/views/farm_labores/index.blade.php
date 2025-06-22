@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Farm Labores</h2>
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
            <table class="table">
                <thead class="thead-dark">
                    <tr>

                        <th> ID</th>
                        <th>Name</th>
                        <th>Phone number</th>
                        <th>Address</th>
                        <th>Experience</th>
                      

                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                    <tr>
                        @foreach($farm_labores as $farm_labore)

                        <td>{{$farm_labore->id}}</td>
                        <td>{{$farm_labore->user->name}}</td>
                        <td>{{$farm_labore->user->phone_number}}</td>
                        <td>{{$farm_labore->user->address}}</td>
                        <td>{{$farm_labore->experience}}</td>
                      
                        <td>

                        <a href="{{ route('farm_labore.view',$farm_labore->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('farm_labore.edit',$farm_labore->id) }}" class="btn btn-primary">Edit</a>
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
   function confirmDelete(retailerId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the retailer record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/users/retailer_list/${retailerId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


