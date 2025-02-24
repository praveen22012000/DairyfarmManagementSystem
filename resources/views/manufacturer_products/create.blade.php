@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Manufacturing Milk Products</h1>

    <form action="" method="POST" class="space-y-8">
        @csrf

        <!-- Date, Time, and Entered By Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded"
                  required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <br>
                <input type="time" name="time" id="time"  class="form-control rounded"
                    class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>

            <div>
                <label for="entered_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <br>
                <input type="text" name="entered_by" id="entered_by" placeholder="Enter your name" class="form-control rounded"
                    class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('entered_by') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
        </div>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table  class="table">
                <thead  class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Product</th>
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Manufacture
                            Date</th>
                        <th class="border-b px-6 py-4 text-left">ExpiryDate</th>
                        <th class="border-b px-6 py-4 text-left">Manufactured By</th>

                    </tr>
                </thead>
              
        <tbody>

                <tr class="hover:bg-gray-50">
                    <!-- ID -->
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                                <select name="product_id" id="product_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Product</option>
                                            @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id}}">{{ $milkProduct->product_name }}</option>
                                            @endforeach
                                </select>
                            @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- MilkDetails (Smaller Width) -->
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="text" name="quantity[]" class="border border-gray-400 rounded-lg px-2 py-1 w-24 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- Stock Quantity (Smaller Width) -->
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="date" name="manufacture_date" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('manufacture_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- Quantity (Smaller Width) -->
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="date" name="expire_date" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('expire_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="user_id" id="user_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Farm Labor</option>
                                        @foreach($farm_labors as $farm_labor)
                                    <option value="{{$farm_labor->id}}">{{$farm_labor->name}}</option>
                                        @endforeach
                            </select>
                            @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                </tr>
        </tbody>

     
        <div class="col-md-12" id="Fields">

            <label for="ingredients">Ingredients</label>
                <input type="text" name="ingredients[]" class="col-md-12 form-control rounded"  required>  
            
                <br>

        </div>



            </table>
        </div>


        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" class="btn btn-success mt-3">Save Manufacture Product</button>
        </div>
    </form>
</div>


@endsection

      
@section('js')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("date").setAttribute("max", today);
    });
</script>


@endsection
