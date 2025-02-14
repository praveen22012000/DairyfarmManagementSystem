@extends('layouts.admin.master')

@section('content')
       
<div class="container mx-auto py-12 px-8 bg-gray-100 rounded-lg shadow-lg">
    <h1 class="text-4xl font-bold mb-12 text-center text-gray-800">Milk Production Records</h1>

    <form action="" method="POST" class="space-y-12">
        @csrf

        <!-- Date, Time, and Entered By Section -->
        <div class="space-y-6">
            <div>
                <label for="date" class="block text-xl font-medium text-gray-800 mb-2">Date</label>
                <input type="date" name="date" id="date" 
                    class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="time" class="block text-xl font-medium text-gray-800 mb-2">Time</label>
                <input type="time" name="time" id="time" 
                    class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="entered_by" class="block text-xl font-medium text-gray-800 mb-2">Entered By</label>
                <input type="text" name="entered_by" id="entered_by" placeholder="Enter your name" 
                    class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('entered_by') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse bg-white shadow-lg rounded-lg">
                <thead class="bg-blue-200 text-gray-800 text-md uppercase tracking-wide">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Product</th>
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Manufacture Date</th>
                        <th class="border-b px-6 py-4 text-left">Expiry Date</th>
                        <th class="border-b px-6 py-4 text-left">Manufactured By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($milkProducts as $milkProduct)
                        <tr class="hover:bg-gray-100">
                            <td class="border-t px-6 py-4">
                                <select name="product_id" id="product_id" class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Product</option>
                                    @foreach($milkProducts as $milkProduct)
                                        <option value="{{$milkProduct->id}}">{{ $milkProduct->product_name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>

                            <td class="border-t px-6 py-4">
                                <input type="text" name="quantity[]" class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>

                            <td class="border-t px-6 py-4">
                                <input type="date" name="manufacture_date" class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('manufacture_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>

                            <td class="border-t px-6 py-4">
                                <input type="date" name="expire_date" class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('expire_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>

                            <td class="border-t px-6 py-4">
                                <input type="text" name="consumed_quantity[]" class="border border-gray-400 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('consumed_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" 
                class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-md">
                Save Records
            </button>
        </div>
    </form>
</div>


@endsection

      
@section('js')




@endsection
