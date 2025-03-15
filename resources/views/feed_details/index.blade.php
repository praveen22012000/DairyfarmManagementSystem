@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Feed And Vaccine Details </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('feed_vaccine.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Feed And Vaccine Record
                    </a>
               
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
                        <th>Item</th>
                        <th>Manufacturer</th>
                        <th>Unit Type</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                @foreach($feedVaccineDetails as $feedVaccineDetail)
              
                    <tr>
                      

                        <td>{{$feedVaccineDetail->id}}</td>
                        <td>{{$feedVaccineDetail->feed_name}}</td>
                        <td>{{$feedVaccineDetail->manufacturer}}</td>
                    
                        <td>{{$feedVaccineDetail->unit_type}}</td>
                        <td>{{$feedVaccineDetail->unit_price}}</td>
                       
                 

                        <td>

                        <a href="" class="btn btn-info">View</a>
                        <a href="" class="btn btn-primary">Edit</a>
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
   function confirmDelete(manufacturerProductId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Manufacture milk Product record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/milk_products_manufacture_details/${manufacturerProductId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


