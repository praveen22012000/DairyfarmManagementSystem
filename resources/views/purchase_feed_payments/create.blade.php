@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center;">Purchase Feed Payments</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('purchase_feed_payments.store') }}">
        @csrf



        <div class="form-group">
        <label for="purchase_id">Purchase ID</label>
        <select name="purchase_id" id="purchase_id" class="form-control" >
            <option value="">Select Purchase</option>
            @foreach($unpaid_purchase_feeds as $unpaid_purchase_feed)
                <option value="{{ $unpaid_purchase_feed->id }}"
                {{  old('purchase_id') == $unpaid_purchase_feed->id ? 'selected' : ''  }}
                >{{ $unpaid_purchase_feed->purchase_date.'|'.$unpaid_purchase_feed->supplier->name}}</option>
            @endforeach
        </select>
        @error('purchase_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
                <input type="date" name="payment_date" class="form-control rounded" id="payment_date">
                 @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

         <!--this is to get the Animalname-->
         <div class="form-group">
            <label for="payment_amount">Payment Amount</label>
            <input type="text" name="payment_amount" class="form-control rounded" id="payment_amount" readonly>
            @error('payment_amount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


   
        
        <button type="submit" class="btn btn-success mt-3">Register Payment</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () //This tells the browser to run the code only after the entire webpage (DOM) has finished loading.
{
    $('#purchase_id').change(function () // This defines a function named fetchPaymentAmount that takes one argument: purchaseId
    {
        var purchaseId = $(this).val();

        if (purchaseId !== "") // If the purchaseId is not empty
        {
            $.ajax({ //This is an AJAX (Asynchronous JavaScript and XML) call using jQuery.

                url: '/purchase_feed_items_payments/get-purchase-amount/' + purchaseId,//This is the URL the AJAX call will contact.
                method: 'GET',// The type of HTTP request is GET
                success: function (response) //  If the request is successful, this function will execute.
                {
                    $('#payment_amount').val(response.total_amount);// Sets the value of the input field with id="payment_amount" to response.total_amount.
                },
                error: function () //  If the AJAX request fails, this function will run.
                {
                    $('#payment_amount').val('');// Clears the payment amount input field.

                    alert('Failed to fetch payment amount.');// Shows an alert box with this error message.
                }
            });
        } else 
        {
            $('#payment_amount').val('');// Clears the payment amount field if nothing is selected.
        }
    });
});
</script>



<script>






</script>

@endsection