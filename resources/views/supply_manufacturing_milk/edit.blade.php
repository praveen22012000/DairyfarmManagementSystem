@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Milk Consumption For Manufacturing Products </h1>

    <form action="{{route('milk_allocated_for_manufacturing.update',$productionSupplyDetails->id)}}" method="POST" class="space-y-8">
        @csrf

        <br>

        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" value="{{$productionSupplyDetails->production_supply->date}}" required>
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <br>
                <input type="time" name="time" id="time" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" value="{{$productionSupplyDetails-> production_supply->time}}"required>
                    @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
           
            
        </div>

        
         <!-- Milk Production Table -->
         <div class="overflow-x-auto">
            <table id="milkTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">MilkDetails</th>
                        <th class="border-b px-6 py-4 text-left">Stock Quantity (SQ)</th>
                        <th class="border-b px-6 py-4 text-left">Consumed Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Product</th>
                        
                    </tr>
                </thead>
                <tbody>
                    
                    
                    <tr class="milk-row">
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="production_milk_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($ProductionsMilk as $ProMilk)
                                    <option value="{{$ProMilk->id}}" 
                                    {{ $productionSupplyDetails->production_milk_id==$ProMilk->id ? 'selected':''}}
                                    data-stock_quantity="{{ $ProMilk->stock_quantity }}"
                                   >
                                        {{ $ProMilk->AnimalDetail->animal_name.' | '.$ProMilk->production_date.' | '.$ProMilk->shift }}
                                    </option>
                                @endforeach
                            </select>
                            @error("production_milk_id") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="form-control" name="quantity" value="" style="width: 80px;" readonly>
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="consumed_quantity" class="form-control rounded" value="{{$productionSupplyDetails-> consumed_quantity}}">
                            @error("consumed_quantity") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                            <select name="product_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id}}"
                                    {{ $productionSupplyDetails->product_id==$milkProduct->id ? 'selected':''}}
                                       
                                    >
                                        {{ $milkProduct->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error("product_id") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                      
                    </tr>
                 
                </tbody>
            </table>
        </div>
         <!-- Submit Button -->
         <div class="flex justify-center">
                <button type="submit" class="btn btn-success mt-3">Update</button>
        </div>
     
    </form>
</div>



@endsection

      
@section('js')

<script>
  $(document).ready(function () {
    let today = new Date().toISOString().split("T")[0];
    $("#date").attr("max", today);

    // Update stock quantity when selection changes
    $(document).on("change", "select[name='production_milk_id']", function () {
        let selectedOption = $(this).find(":selected");
        let stockQuantity = selectedOption.data("stock_quantity");
        $(this).closest("tr").find("input[name='quantity']").val(stockQuantity);
    });

    // Initialize stock quantity on page load
    let initialSelection = $("select[name='production_milk_id']").find(":selected");
    if (initialSelection.length > 0) {
        let stockQuantity = initialSelection.data("stock_quantity");
        $("input[name='quantity']").val(stockQuantity);
    }
});


</script>


@endsection
