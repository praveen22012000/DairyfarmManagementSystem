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

        <label class="w-auto px-2">Payment Receipt (Image or PDF):</label><br>
       
         {{--This below "$receiptPath" gets the file path of the uploaded receipt from the database--}}
        @php
            $receiptPath = $retailor_order->order_payment->payment_receipt ?? null;
        @endphp


            {{-- the explanation for following code are explain here --}} 

            {{--  "$receiptPath" =>  it will continue only $receiptPath exists  --}}

            {{-- "asset('storage/' . $receiptPath)" => "Generates a full URL to the uploaded file" and PATHINFO EXTENSION used to get extension --}}

            {{--  "$extension" => "This line gets the file extension" --}}

            {{-- "in_array" => "Checks if the uploaded file is an image."--}}

    @if ($receiptPath)

        @php
            $fileUrl = asset('storage/' . $receiptPath);
            $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
        @endphp

    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
        <!-- Show image preview -->

       <a href="{{ route('receipts.view', ['path' => $receiptPath]) }}" target="_blank">
        <!--"main purpose => "Sends the path of the uploaded receipt file to the route, so the system can fetch and show the correct file -->
        <!--in above,is the name of the parameter you're sending. -->
        <!--$receiptPath → is the value of that parameter -->


            <img src="{{ asset('storage/' . $receiptPath) }}" alt="Payment Receipt"
            style="max-width: 200px; border: 1px solid #ccc; padding: 5px;">
        <!-- The asset() function is used to generate the full URL to a file-->
        <!-- this generate the full url file for the uploaded image-->
        </a>

    @elseif ($extension === 'pdf')
        <!-- Show PDF link -->
        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-info">View PDF Receipt</a>
    @else
        <p>No valid receipt uploaded.</p>
    @endif
    @else
    <p>No receipt uploaded.</p>
    @endif


        <br>
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