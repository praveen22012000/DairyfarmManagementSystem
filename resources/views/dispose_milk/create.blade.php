@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Dispose Milk Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('dispose_milk.store')}}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


        <div class="form-group">
        <label for="production_milk_id">Milk Production Item</label>
        <select name="production_milk_id" id="production_milk_id" class="form-control" >
            <option value="">Select the Milk Production Item</option>
            @foreach($ProductionsMilks as $ProductionsMilk)
                <option value="{{$ProductionsMilk->id}}">{{ $ProductionsMilk->AnimalDetail->animal_name.'|'. $ProductionsMilk->production_date.'|'.$ProductionsMilk->shift}}</option>
            @endforeach
        </select>
        @error('production_milk_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    
        <div class="form-group">
            <label for="user_id">Farm Labor</label>
           
        <!-- this is used to list the farm labors-->
        <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select Farm Labor</option>
             
            @foreach($farm_labors as $farm_labor)

                <option value="{{$farm_labor->id}}">{{$farm_labor->name}}</option>
            @endforeach

            </select>

            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div>
                <label for="date">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <br>

        
        <div>
            <label for="dispose_quantity">Dispose Quantity</label>
            <br>
            <input type="text" name="dispose_quantity" id="dispose_quantity" placeholder="Enter the Dispose Quantity" class="form-control rounded" required>
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
            <textarea class="form-control" id="reason_for_dispose" name="reason_for_dispose" rows="4" placeholder="Enter the reson for the disposal"></textarea>
            @error('reason_for_dispose') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Dispose Milk Record</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script>

$(document).ready(function () {
    $('#production_milk_id').on('change', function () {
        var productionMilkId = $(this).val(); // Get the selected calf ID
     
        if (productionMilkId) {
            // Send an AJAX request to fetch calving details
            $.ajax({
                url: `/milk_dispose/${productionMilkId}/details`,
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