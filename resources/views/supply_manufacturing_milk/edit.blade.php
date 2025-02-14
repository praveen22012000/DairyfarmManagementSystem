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
            <div>
                <label for="entered_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <br>
                <input type="text" name="entered_by" id="entered_by" placeholder="Enter your name" class="form-control rounded"
                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required value="{{$productionSupplyDetails-> production_supply->time}}">
                    @error('entered_by') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
        </div>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">ID</th>
                        <th class="border-b px-6 py-4 text-left">MilkDetails</th>
                        <th class="border-b px-6 py-4 text-left">Stock Quantity (SQ)</th>
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Product</th>

                    </tr>
                </thead>
                <tbody>
              
                  
                        <tr class="hover:bg-gray-50">
                            <!-- ID -->
                            <td class="border-t px-6 py-4 text-left text-gray-800">
                            {{$productionSupplyDetails->id}}
                            </td>

                             <!-- MilkDetails -->
                             <td class="border-t px-6 py-4 text-left text-gray-800">
                             {{$productionSupplyDetails->production_milk->AnimalDetail->animal_name.'|'.$productionSupplyDetails->production_milk->production_date.'|'.$productionSupplyDetails->production_milk->shift}}
    
                            </td>


                            <!-- Stock Quantity -->
                            <td class="border-t px-6 py-4 text-left text-gray-800">
                            {{$productionSupplyDetails->production_milk->stock_quantity}}
                            </td>

                            <!-- Quantity -->
                            <td class="border-t px-6 py-4 text-left text-gray-800">
                                <input type="text" name="consumed_quantity" value="{{$productionSupplyDetails->consumed_quantity}}" 
                                class="form-control rounded">
                                    @error('consumed_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>

                            

                            <td>
                                <div class="col-md-12">
                                   
                                    <select name="product_id" id="product_id"  class="form-control">

                                        <option value="">Select the Product</option>
                                             @foreach($milkProducts as $milkProduct)
                                                    <option value="{{$milkProduct->id }}"
                                                    {{$productionSupplyDetails->product_id==$milkProduct->id ? "selected" : ''}}
                                                    >{{ $milkProduct->product_name }}</option>
                                         @endforeach
                        
                                    </select>
                                        @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

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




@endsection
