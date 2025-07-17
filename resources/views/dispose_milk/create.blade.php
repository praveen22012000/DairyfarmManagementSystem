@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center;">Dispose Milk Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('dispose_milk.store')}}">
        @csrf

       


        <div class="form-group">
        <label for="production_milk_id">Milk Production Item</label>
        <select name="production_milk_id" id="production_milk_id" class="form-control" >
            <option value="">Select the Milk Production Item</option>
            @foreach($ProductionsMilks as $ProductionsMilk)
                <option value="{{$ProductionsMilk->id}}"
                
                {{ old('production_milk_id')== $ProductionsMilk->id ? 'selected':'' }}
                >{{ $ProductionsMilk->AnimalDetail->animal_name.'|'. $ProductionsMilk->production_date.'|'.$ProductionsMilk->shift}}</option>
            @endforeach
        </select>
        @error('production_milk_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    
        <div class="form-group">
            <label for="user_id">Farm Labor</label>
           
        <!-- this is used to list the farm labors-->
        <select name="user_id" id="user_id" class="form-control" >
                <option value="">Select Farm Labor</option>
             
            @foreach($farm_labors as $farm_labor)

                <option value="{{$farm_labor->id}}"
                {{ old('user_id')== $farm_labor->id ? 'selected':''  }}
                >{{$farm_labor->name}}</option>
            @endforeach

            </select>

            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div>
                <label for="date">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" value="{{ old('date') }}">
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <br>

        
        <div>
            <label for="dispose_quantity">Dispose Quantity</label>
            <br>
            <input type="text" name="dispose_quantity" id="dispose_quantity" placeholder="Enter the Dispose Quantity" class="form-control rounded" value="{{ old('dispose_quantity') }}">
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
            <textarea class="form-control" id="reason_for_dispose" name="reason_for_dispose" rows="4" placeholder="Enter the reson for the disposal">{{ old('reason_for_dispose') }}</textarea>
            @error('reason_for_dispose') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

       
        
        <button type="submit" class="btn btn-success mt-3">Dispose Milk Record</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




<script>
$(document).ready(function () {
    $('#production_milk_id').on('change', function () {
        fetchStockQuantity();
    });

    //  This runs on page load if there's an old value
    @if(old('production_milk_id'))
        $('#production_milk_id').val('{{ old('production_milk_id') }}');
        fetchStockQuantity();
    @endif

    function fetchStockQuantity() {
        var productionMilkId = $('#production_milk_id').val();
        if (productionMilkId) {
            $.ajax({
                url: `/milk_dispose/${productionMilkId}/details`,
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
        } else {
            $('#available_stock_quantity').val('');
        }
    }
});



</script>






@endsection