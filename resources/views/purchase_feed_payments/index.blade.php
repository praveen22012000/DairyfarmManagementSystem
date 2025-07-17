@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Payments For Purchase Feed</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{route('purchase_feed_payments.create')}}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Payment For Feed
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="purchaseFeedPaymentTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th> ID</th>
                        <th>Purchased ID</th>
                        <th>Payment Receiver </th>
                        <th>Amount</th> 
                        <th>Paid Date</th>
                        <th>Reference Number</th>
                       
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($purchase_feed_payments as $purchase_feed_payment)
                    <tr>
                      

                        <td>{{$purchase_feed_payment->id}}</td>
                        <td>{{$purchase_feed_payment->purchase_feed->supplier->name.'|'.$purchase_feed_payment->purchase_feed->purchase_date}}</td>
                       
                        <td>{{$purchase_feed_payment->user->name}}</td>
                        <td>{{$purchase_feed_payment->payment_amount }}</td>
                        <td>{{$purchase_feed_payment->payment_date}}</td>
                        <td>{{$purchase_feed_payment->reference_number}}</td>
                 

                        <td>

                        <a href="{{ route('payment.slip.download',$purchase_feed_payment->id)  }}" class="btn btn-secondary">Slip</a>
                        <a href="{{ route('purchase_feed_payments.view',$purchase_feed_payment->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('purchase_feed_payments.edit',$purchase_feed_payment->id)  }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $purchase_feed_payment->id }})">Delete</button>
                    
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
   function confirmDelete(purchaseFeedPaymentId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the Payment for the feed record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/purchase_feed_items_payments/${purchaseFeedPaymentId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>


<script>
$(document).ready(function() {
    $('#purchaseFeedPaymentTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For Feed Payment records:"
        }
    });
});
</script>


@endsection


