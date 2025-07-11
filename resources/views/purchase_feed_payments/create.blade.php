@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Purchase Feed Payments</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('purchase_feed_payments.store') }}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


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


        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Register Payment</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#purchase_id').change(function () {
        var purchaseId = $(this).val();

        if (purchaseId !== "") {
            $.ajax({
                url: '/purchase_feed_items_payments/get-purchase-amount/' + purchaseId,
                method: 'GET',
                success: function (response) {
                    $('#payment_amount').val(response.total_amount);
                },
                error: function () {
                    $('#payment_amount').val('');
                    alert('Failed to fetch payment amount.');
                }
            });
        } else {
            $('#payment_amount').val('');
        }
    });
});
</script>



<script>






</script>

@endsection