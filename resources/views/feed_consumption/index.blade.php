@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Feed Consumption Details  </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('feed_consume_items.create')  }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Add a Feed Consumption
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
                        <th>ID</th>
                        <th>Animal </th>
                        <th>Feed Item</th>
                        <th>Feed Date & Time</th>
                        <th>Feed Amount</th>
                        <th>notes</th>
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                 
                @foreach($feedConsumeItems as $feedConsumeItem)
                    <tr>
                      

                        <td>{{$feedConsumeItem->id}}</td>
                        <td>{{$feedConsumeItem->feed_consume_details->animal_details->animal_name}}</td>
                        <td>{{$feedConsumeItem->feed->feed_name}}</td>
                        <td>{{$feedConsumeItem->feed_consume_details->date.'|'.$feedConsumeItem->feed_consume_details->time}}</td>
                        <td>{{$feedConsumeItem->consumed_quantity}}</td>
                        <td>{{$feedConsumeItem->notes}}</td>
                     
                        
                
                        <td>

                        <a href="{{ route('feed_consume_items.view',$feedConsumeItem->id)  }}" class="btn btn-info">View</a>
                        <a href="{{ route('feed_consume_items.edit',$feedConsumeItem->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $feedConsumeItem->id }})">Delete</button>
                    
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
   function confirmDelete(feedConsumeItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Feed Consume Item record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/feed_consume_items_by_animals/${feedConsumeItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


