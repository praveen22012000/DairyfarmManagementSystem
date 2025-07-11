@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center">Dispose Milk Products Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('dispose_milk_product.store')}}">
        @csrf

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


        <div class="form-group">
        <label for="manufacturer_product_id">Milk Production Item</label>
        <select name="manufacturer_product_id" id="manufacturer_product_id" class="form-control" >
            <option value="">Select the Milk Production Item</option>
      
            @foreach($manufacturedMilkProducts as $manufacturedMilkProduct)
                <option value="{{$manufacturedMilkProduct->id}}"
                {{ old('manufacturer_product_id') == $manufacturedMilkProduct->id ? 'selected' : '' }}
                >{{$manufacturedMilkProduct->id.'|'.$manufacturedMilkProduct->milk_product->product_name.'|'.$manufacturedMilkProduct->manufacture_date}}</option>
                @endforeach
        </select>
        @error('manufacturer_product_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    
        <div class="form-group">
            <label for="user_id">Farm Labor</label>
           
        <!-- this is used to list the farm labors-->
        <select name="user_id" id="user_id" class="form-control">

                <option value="">Select Farm Labor</option>
             
                @foreach($farm_labors as $farm_labor)
                <option value="{{$farm_labor->id}}"
                {{ old('user_id') == $farm_labor->id ? 'selected':'' }}
                >{{$farm_labor->name}}</option>
                @endforeach

        </select>

            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div>
                <label for="date">Date</label>
                <br>
                <input type="date" value="{{ old('date') }}" name="date" id="date" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <br>

        
        <div>
            <label for="dispose_quantity">Dispose Quantity</label>
            <br>
            <input type="text" name="dispose_quantity" id="dispose_quantity" value="{{ old('dispose_quantity') }}" placeholder="Enter the Dispose Quantity" class="form-control rounded">
                @error('dispose_quantity') 
                    <span class="text-danger">{{ $message }}</span> 
                @enderror
        </div>

        <br>
    
        <div>
                <label for="available_stock_quantity">Available Stock Quantity</label>
                <br>
                <input type="text" name="available_stock_quantity" id="available_stock_quantity"  class="form-control rounded" readonly 
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('available_stock_quantity') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <br>
       
        <!--this is used to mention the reason foir the disposal-->
        <div class="form-group">
            <label for="reason_for_dispose">Reason For Milk Disposal</label>
            <textarea class="form-control" id="reason_for_dispose" name="reason_for_dispose" rows="4" placeholder="Enter the reson for the disposal">{{ old('reason_for_dispose') }}</textarea>
            @error('reason_for_dispose') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Dispose Record</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script>

$(document).ready(function () {
    $('#manufacturer_product_id').on('change', function () {
        var manufacturerProductId = $(this).val(); // Get the selected calf ID
     
        if (manufacturerProductId) {
            // Send an AJAX request to fetch calving details
            $.ajax({
                url: `/dispose_milk_products/${manufacturerProductId}/details`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // If data is returned, populate the form fields
                    if (data) {
                        $('#available_stock_quantity').val(data.stock_quantity);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching stock quantity details:', error);
                }
            });
        }
    });
});



</script>






@endsection