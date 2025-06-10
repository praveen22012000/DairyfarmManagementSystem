@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Purchase Feed Items </h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('purchase_feed_items.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Purchase Feed Item
                    </a>
               
                </div>

            </div>

                     <!-- start-->
        <div class="card-header">
            
                <a class="btn btn-primary" href="{{ route('report.monthly_feed_purchase') }}">
                     View Monthly Chart
                </a>   
                
                
                <a class="btn btn-primary" href="{{ route('reports.feed_spending_for_each_product') }}">
                     View Monthly Product Price Chart
                </a>  
                  
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
                        <th>Supplier</th>
                        <th>Feed Name </th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Manufacture Date</th>
                        <th>Expire Date</th>
                       
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($purchaseFeedItems as $purchaseFeedItem)
                    <tr>
                      

                        <td>{{$purchaseFeedItem->id}}</td>
                        <td>{{$purchaseFeedItem->purchase_feed->supplier->name}}</td>
                        <td>{{$purchaseFeedItem->feed->feed_name}}</td>
                        <td>{{$purchaseFeedItem->unit_price}}</td>
                        <td>{{$purchaseFeedItem->purchase_quantity}}</td>
                        <td>{{$purchaseFeedItem->manufacture_date}}</td>
                        <td>{{$purchaseFeedItem->expire_date}}</td>
                 

                        <td>

                        <a href="{{ route('purchase_feed_items.view',$purchaseFeedItem->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('purchase_feed_items.edit',$purchaseFeedItem->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $purchaseFeedItem->id }})">Delete</button>
                    
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
   function confirmDelete(purchaseFeedItemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Feed Purchase record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/purchase_feed_items/${purchaseFeedItemId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection


