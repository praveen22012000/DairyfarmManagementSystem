@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Dispose Feed Items </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('dispose_feed_items.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Dispose Record
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
                        <th> Feed </th>
                        <th>Purchase Date</th>
                        <th>Manufacture Date</th>
                        <th>Expire Date</th>
                        <th> Dispose Quantity </th>
                        <th> Reason</th>
                 
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($disposeFeedItems as $disposeFeedItem)
                    <tr>
                      

                        <td>{{$disposeFeedItem->id}}</td>
                        <td>{{$disposeFeedItem->purchase_feed_items->feed->feed_name}}</td>
                        <td>{{$disposeFeedItem->purchase_feed_items->purchase_feed->purchase_date}}</td>
                        <td>{{$disposeFeedItem->purchase_feed_items->manufacture_date}}</td>
                        <td>{{$disposeFeedItem->purchase_feed_items->expire_date}}</td>
                        <td>{{$disposeFeedItem->dispose_quantity}}</td>
                        <td>{{$disposeFeedItem->reason_for_dispose}}</td>
                        
                        
                       
                 

                        <td>

                        <a href="{{ route('dispose_feed_items.view',$disposeFeedItem->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('dispose_feed_items.edit',$disposeFeedItem->id) }}" class="btn btn-primary">Edit</a>
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
   function confirmDelete(disposeFeedItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Dispose Feed Item record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/dispose_feed_items/${disposeFeedItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


