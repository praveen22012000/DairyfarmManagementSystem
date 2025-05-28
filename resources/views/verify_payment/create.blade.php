@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">


       
            <h1>Retailor Payment Form</h1>     

    <br>

    <form method="POST" action="" enctype="multipart/form-data">
        @csrf

        

        <label class="w-auto px-2">Transaction ID:</label>
        <input type="text" name="transaction_id" value="{{ $retailor_order->order_payment->transaction_id }}" class="form-control rounded" > <br>

        <label class="w-auto px-2">Payment Date:</label>
        <input type="date" name="payment_date" value="{{ $retailor_order->order_payment->payment_date }}"  class="form-control rounded" > <br>

        <label class="w-auto px-2">Amount Paid:</label>
        <input type="number" step="0.01" name="amount_paid" readonly value="{{ $retailor_order->total_amount }}" class="form-control rounded"><br>

        <label class="w-auto px-2">Payment Receipt (Image or PDF):</label>
        <input type="file" name="payment_receipt" accept="image/*,application/pdf" class="form-control rounded">

        <br>
    
    </form>

    <form action="{{ route('verify_payment.receipt.accept', $retailor_order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Accept</button>
    </form>

    <form action="{{ route('verify_payment.receipt.reject', $retailor_order->id) }}" method="POST" style="display:inline;">
                    @csrf
              
                    <button type="submit" class="btn btn-danger">Reject</button>
    </form>
    
</div>

@endsection
