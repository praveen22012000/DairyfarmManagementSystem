@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Dispose Milk Products Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="">
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
        


        <div class="form-group">
        <label for="manufacturer_product_id">Milk Production Item</label>
        <select name="manufacturer_product_id" id="manufacturer_product_id" class="form-control" >
            <option value="">Select the Milk Production Item</option>
      
            @foreach($manufacturedMilkProducts as $manufacturedMilkProduct)
                <option value="{{$manufacturedMilkProduct->id}}"
                {{$disposeMilkProducts->manufacturer_product_id==$manufacturedMilkProduct->id ? 'selected' : ''}}
                >{{$manufacturedMilkProduct->id.'|'.$manufacturedMilkProduct->milk_product->product_name.'|'.$manufacturedMilkProduct->manufacture_date}}</option>
                @endforeach
        </select>
        @error('manufacturer_product_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    
        <div class="form-group">
            <label for="user_id">Farm Labor</label>
           
        <!-- this is used to list the farm labors-->
        <select name="user_id" id="user_id" class="form-control" required>

                <option value="">Select Farm Labor</option>
             
                @foreach($farm_labors as $farm_labor)
                <option value="{{$farm_labor->id}}"
                {{$disposeMilkProducts->user_id==$farm_labor->id ? 'selected' : ''}}
                >{{$farm_labor->name}}</option>
                @endforeach

        </select>

            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div>
                <label for="date">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded" value="{{$disposeMilkProducts->date}}"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <br>

        
        <div>
            <label for="dispose_quantity">Dispose Quantity</label>
            <br>
            <input type="text" name="dispose_quantity" id="dispose_quantity" value="{{$disposeMilkProducts->dispose_quantity}}" placeholder="Enter the Dispose Quantity" class="form-control rounded" required>
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
            <label for="reason_for_dispose">Reason For Milk Product Disposal</label>
            <textarea class="form-control" id="reason_for_dispose" name="reason_for_dispose" rows="4" placeholder="Enter the reson for the disposal">{{$disposeMilkProducts->reason_for_dispose}}</textarea>
            @error('reason_for_dispose') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
    
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script>

$(document).ready(function () {
    function fetchStockQuantity(manufacturerProductId) {
        if (manufacturerProductId) {
            $.ajax({
                url: `/milk_dispose/${manufacturerProductId}/details`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data) {
                        $('#available_stock_quantity').val(data.stock_quantity);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching stock quantity details:', error);
                }
            });
        }
    }

    // Fetch stock quantity when the page loads (if manufacturer_product_id is pre-selected)
    var initialManufacturerProductId = $('#manufacturer_product_id').val();
    fetchStockQuantity(initialManufacturerProductId);

    // Fetch stock quantity when the dropdown selection changes
    $('#manufacturer_product_id').on('change', function () {
        var selectedManufacturerProductId = $(this).val();
        fetchStockQuantity(selectedManufacturerProductId);
    });
});


</script>






@endsection