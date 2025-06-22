@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


       
            <h1>Retailor Payment Form</h1>     

    <br>

    <form method="POST" action="{{ route('upload_payment.receipt.update',$retailor_order->id) }}" enctype="multipart/form-data">
        @csrf

        

        <label class="w-auto px-2">Payment Date:</label>
        <input type="date" name="payment_date" value="{{ $retailor_order->order_payment->payment_date }}"  class="form-control rounded" > <br>

        <label class="w-auto px-2">Amount Paid:</label>
        <input type="number" step="0.01" name="amount_paid" readonly value="{{ $retailor_order->total_payable_amount }}" class="form-control rounded"><br>

        <label class="w-auto px-2">Payment Receipt (Image or PDF):</label>
        <input type="file" name="payment_receipt" accept="image/*,application/pdf" class="form-control rounded">

        <button type="submit" class="btn btn-primary">Update Payment</button>
    </form>

     <br>
    
    

    <form action="" id="cancelPaymentForm" method="POST" style="display:inline;">
                    @csrf
              
                    <button type="button" onclick="confirmCancel({{ $retailor_order->id }})" class="btn btn-danger">Delete Payment</button>
    </form>
</div>

@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>


 function confirmCancel(retailorId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the order payment record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let cancelPaymentForm = document.getElementById("cancelPaymentForm");
                cancelPaymentForm.action = `upload_payment_details/retailor/cancel_payment_receipt/${retailorId}/cancel`;
                cancelPaymentForm.submit();
            }
        });
    }

</script>
@endsection