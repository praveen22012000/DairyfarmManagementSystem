@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

{{ $retailor_order }}
       
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
    <button type="submit" class="btn btn-success mt-3">Upload Payment</button>
    </form>

    
</div>

@endsection
