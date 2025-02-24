@extends('layouts.admin.master')

@section('content')

<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Milk Consumption For Manufacturing Products </h1>

    <form action="{{route('milk_allocated_for_manufacturing.store')}}" method="POST" class="space-y-8">
        @csrf

        <br>

     


        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <br>
                <input type="date" name="date" id="date" class="form-control rounded" required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>

            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <br>
                <input type="time" name="time" id="time" class="form-control rounded" required>
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
            <div>
                <label for="entered_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <br>
                <input type="text" name="entered_by" id="entered_by" placeholder="Enter your name" class="form-control rounded" required>
                @error('entered_by') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <th class="border-b px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="milk-row">
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="production_milk_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($ProductionsMilk as $ProMilk)
                                    <option value="{{$ProMilk->id}}">{{ $ProMilk->AnimalDetail->animal_name.' | '.$ProMilk->production_date.' | '.$ProMilk->shift }}</option>
                                @endforeach
                            </select>
                            @error('production_milk_id.*') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800"></td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="consumed_quantity[]" class="form-control rounded">
                            @error('consumed_quantity.*') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                            <select name="product_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id }}">{{ $milkProduct->product_name }}</option>
                                @endforeach
                            </select>
                            @error('product_id.*') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4">
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add Row Button -->
        <div class="flex justify-center mt-3">
            <button type="button" id="addRow" class="btn btn-primary">Add</button>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" class="btn btn-success mt-3">Save Milk Consumption Record</button>
        </div>
    </form>
</div>

@endsection

@section('js')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("date").setAttribute("max", today);

        document.getElementById("addRow").addEventListener("click", function () {
            let table = document.getElementById("milkTable").getElementsByTagName('tbody')[0];
            let newRow = table.rows[0].cloneNode(true);

            // Clear input values in the new row
            newRow.querySelectorAll("input").forEach(input => input.value = "");
            newRow.querySelectorAll("select").forEach(select => select.selectedIndex = 0);

            table.appendChild(newRow);
        });

        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-row")) {
                let row = e.target.closest("tr");
                let table = document.getElementById("milkTable").getElementsByTagName('tbody')[0];

                // Ensure at least one row remains
                if (table.rows.length > 1) {
                    row.remove();
                } else {
                    alert("At least one row is required.");
                }
            }
        });
    });
</script>

<script>



</script>

@endsection
