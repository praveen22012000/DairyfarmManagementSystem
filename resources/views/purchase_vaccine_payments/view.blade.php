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


       

        
        
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    /*
$(document).ready(function () {
    function fetchPaymentAmount(purchaseId) {
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
    }

    // Trigger on change
    $('#purchase_id').change(function () {
        var purchaseId = $(this).val();
        fetchPaymentAmount(purchaseId);
    });

    // Trigger on page load if already selected
    var selectedPurchaseId = $('#purchase_id').val();
    if (selectedPurchaseId) {
        fetchPaymentAmount(selectedPurchaseId);
    }
});*/
</script>

@endsection