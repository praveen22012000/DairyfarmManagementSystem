@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Supplier Details </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('supply_feed_vaccine.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Supplier
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
                        <th>Name </th>
                        <th>Phone Number </th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Feed</th>
                        <th>Vaccine</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($suppliers as $supplier)
                    <tr>
                      

                        <td>{{$supplier->id}}</td>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->phone_no}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>{{$supplier->address}}</td>
                        <td>{{$supplier->feeds->pluck('feed_name')->implode(', ') }}</td>
                        <td>{{$supplier->vaccines->pluck('vaccine_name')->implode(', ') }}</td>
                 

                        <td>

                        <a href="{{ route('milk_product.view',$supplier->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('supply_feed_vaccine.edit',$supplier->id) }}" class="btn btn-primary">Edit</a>
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
   function confirmDelete(milkProductId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the milk Product record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/milk_product_details/${milkProductId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


