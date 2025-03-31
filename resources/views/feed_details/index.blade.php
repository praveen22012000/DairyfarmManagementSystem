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
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Feed  Record
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
                @foreach($feedDetails as $feedDetail)
              
                    <tr>
                      

                        <td>{{$feedDetail->id}}</td>
                        <td>{{$feedDetail->feed_name}}</td>
                        <td>{{$feedDetail->manufacturer}}</td>
                    
                        <td>{{$feedDetail->unit_type}}</td>
                        <td>{{$feedDetail->unit_price}}</td>
                       
                 

                        <td>

                        <a href="{{route('feed_vaccine.view',$feedDetail->id)}}" class="btn btn-info">View</a>
                        <a href="{{route('feed_vaccine.edit',$feedDetail->id)}}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{$feedDetail->id}})">Delete</button>
                    
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
   function confirmDelete(feedDetailId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Feed record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/feed_details/${feedDetailId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


