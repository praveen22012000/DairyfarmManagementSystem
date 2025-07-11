@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center;">Purchase Vaccine Payments</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('purchase_vaccine_payments.update',$purchasevaccinepayment->id) }}">
        @csrf



        <div class="form-group">
        <label for="purchase_id">Purchase ID</label>
        <select name="purchase_id" id="purchase_id" class="form-control" >
            <option value="">Select Purchase</option>
            @foreach($paid_purchase_vaccines as $paid_purchase_vaccine)
            {{ $paid_purchase_vaccine->id  }}
                <option value="{{ $paid_purchase_vaccine->id }}"
                {{$purchasevaccinepayment->purchase_id== $paid_purchase_vaccine->id ?'selected':''}}
                >{{ $paid_purchase_vaccine->id.'|'.$paid_purchase_vaccine->purchase_date.'|'.$paid_purchase_vaccine->supplier->name}}</option>
            @endforeach
        </select>
        @error('purchase_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
                <input type="date" name="payment_date" class="form-control rounded" value="{{ $purchasevaccinepayment->payment_date   }}" id="payment_date">
        </div>

         <!--this is to get the Animalname-->
         <div class="form-group">
            <label for="payment_amount">Payment Amount</label>
            <input type="text" name="payment_amount" class="form-control rounded" value="{{ $purchasevaccinepayment->payment_amount }}" id="payment_amount" readonly>
            @error('payment_amount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


      

        
        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    
$(document).ready(function () {
    $('#purchase_id').on('change', function () {// When the dropdown with the ID purchase_id (the vaccine item dropdown) is changed (i.e., a user selects a different option), run the following code.”
        var purchaseId = $(this).val();//Get the currently selected value from the dropdown.

        if (purchaseId) // run the following code when purchaseId has the value
        {
            $.ajax({ // This starts an AJAX request
                url: '/purchase_vaccine_items_payments/get-vaccine-payment-amount/' + purchaseId, //This is the URL that the AJAX call will send a request to.It dynamically appends the selected purchaseId to the URL.
                type: 'GET', // Use a GET request to fetch data from the server.”
                dataType: 'json', // Expect the server to send back JSON data
                success: function (response) //  If the request is successful (server responds correctly), this function will run.
                {
                    $('#payment_amount').val(response.amount);// Sets the value of the input box with ID payment_amount to the amount received from the server.
                },
                error: function () {
                    $('#payment_amount').val(0);// Set the payment amount input to 0 as a fallback.
                }
            });
        } else {
            $('#payment_amount').val(''); // It clears the payment_amount input field.
        }
    });
});


</script>

@endsection