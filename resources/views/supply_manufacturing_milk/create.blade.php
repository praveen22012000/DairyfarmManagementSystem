@extends('layouts.admin.master')

@section('content')
<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Milk Consumption For Manufacturing Products</h1>

    <form action="{{route('milk_allocated_for_manufacturing.store')}}" method="POST" class="space-y-8">
        @csrf

        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" id="date" class="form-control rounded" value="{{old('date')}}" required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <input type="time" name="time" id="time" class="form-control rounded" value="{{old('time')}}" required>
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="entered_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <input type="text" name="entered_by" id="entered_by" placeholder="Enter your name" class="form-control rounded" value="{{old('entered_by')}}" required>
                @error('entered_by') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <br>

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
                    @php
                        $oldProductionMilkIds = old('production_milk_id', []);
                        $oldConsumedQuantities = old('consumed_quantity', []);
                        $oldProductIds = old('product_id', []);
                        $rowCount = max(count($oldProductionMilkIds), 1);
                    @endphp

                    @for ($i = 0; $i < $rowCount; $i++)
                    <tr  class="milk-row">
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="production_milk_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($ProductionsMilk as $ProMilk)
                                    <option value="{{$ProMilk->id}}" 
                                        {{ (isset($oldProductionMilkIds[$i]) && $oldProductionMilkIds[$i] == $ProMilk->id) ? 'selected' : '' }}
                                        data-stock_quantity="{{ $ProMilk->stock_quantity }}"
                                        >
                                        {{ $ProMilk->AnimalDetail->animal_name.' | '.$ProMilk->production_date.' | '.$ProMilk->shift }}
                                    </option>
                                @endforeach
                            </select>
                            @error("production_milk_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="form-control" name="quantity[]" value="{{ isset($oldProductionMilkIds[$i]) ? ($ProductionsMilk->firstWhere('id', $oldProductionMilkIds[$i])?->stock_quantity ?? '') : '' }}" style="width: 90px;" readonly>
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="consumed_quantity[]" class="form-control rounded" value="{{ $oldConsumedQuantities[$i] ?? '' }}">
                            @error("consumed_quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                            <select name="product_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Product</option>
                                @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id}}" 
                                        {{ (isset($oldProductIds[$i]) && $oldProductIds[$i] == $milkProduct->id) ? 'selected' : '' }}>
                                        {{ $milkProduct->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error("product_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4">
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                    @endfor
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
   $(document).ready(function () {
   let today = new Date().toISOString().split("T")[0];
    $("#date").attr("max", today);


      // Restore stock quantity on page load
      //Selects all <select> dropdowns that have the name production_milk_id[]. (Each row in your table has one.)
      $("select[name='production_milk_id[]']").each(function () {
        let selectedOption = $(this).find(":selected");//Gets the currently selected option in that dropdown.
        let stockQuantity = selectedOption.data("stock_quantity");//Retrieves the value of the custom attribute data-stock_quantity from the selected option.

        //Finds the nearest <tr> //Finds the quantity[] input in that row, // Sets its value to the stock quantity of the selected milk item.
        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });

    // Update stock quantity dynamically on selection change
    $(document).on("change", "select[name='production_milk_id[]']", function () {
        let selectedOption = $(this).find(":selected");
        let stockQuantity = selectedOption.data("stock_quantity");

        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });



    // Function to check for validation errors in the table rows
    function checkForErrors() {
        let hasErrors = false;

        // Loop through all rows and check for error messages
        $("#milkTable tbody tr").each(function () {
            if ($(this).find(".text-danger").length > 0) {
                hasErrors = true;
                return false; // Exit the loop if an error is found
            }
        });

        // Hide the "Add" button if there are errors
        if (hasErrors) {
            $("#addRow").hide();
        } else {
            updateAddButtonVisibility(); // Otherwise, update visibility based on row count
        }
    }


    //This function controls whether the "Add" button (#addRow) should be shown or hidden.
    function updateAddButtonVisibility() {

        //Finds the first <tr> in #milkTable tbody (first row of the table).
        //Retrieves the number of <option> elements inside the production_milk_id[] dropdown but subtracts 1 to exclude the default "Select the Product" option.
        //Counts the total rows currently present in the table.
        let totalMilkItems = $("#milkTable tbody tr:first").find("select[name='production_milk_id[]'] option").length - 1; // Exclude the default option
        let totalRows = $("#milkTable tbody tr").length;


        //If the number of rows equals or exceeds the number of available milk items, the "Add" button is hidden.
        //Otherwise, the "Add" button remains visible.
        if (totalRows >= totalMilkItems) {
            $("#addRow").hide();
        } else {
            $("#addRow").show();
        }
    }

    $("#addRow").click(function () {

        //Find the Table and Clone the First Row
        let table = $("#milkTable tbody");
        let newRow = table.find("tr:first").clone();

        // Clear input values in the new row
        
        newRow.find("input").val("");//Empties all input fields (.val("")) to prevent duplicate values.
        newRow.find("select").prop("selectedIndex", 0);//Resets dropdowns (prop("selectedIndex", 0)) to the first (default) option

        table.append(newRow);//Adds the new row to the table (append(newRow)

        updateMilkItemOptions(); // Update options in all rows
        updateAddButtonVisibility(); // Check visibility of the Add button
    });

    // Remove a Row When Clicking the "Remove" Button
    $(document).on("click", ".remove-row", function () {
        //Uses $(document).on() because .remove-row buttons are dynamically added (not present when the page loads).

        //Selects the table body.
        let table = $("#milkTable tbody");

        //Checks if there is more than one row in the table.
        if (table.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateMilkItemOptions(); // Re-update dropdowns when a row is removed
            updateAddButtonVisibility(); // Check visibility of the Add button
        } else {
            alert("At least one row is required.");
        }
    });

  //  Ensures that each row has a unique milk item selection.
    function updateMilkItemOptions() {

        let selectedItems = [];//Creates an empty array selectedItems.

       // Loops through all dropdowns named production_milk_id[] and stores selected values in selectedItems.
        $("select[name='production_milk_id[]']").each(function () {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedItems.push(selectedValue);
            }
        });

        

        //It iterates over all <select> dropdowns that have the name production_milk_id[] and hides any option 
        // --that has already been selected in another dropdown
        $("select[name='production_milk_id[]']").each(function () {
            let currentValue = $(this).val();
            $(this).find("option").each(function () {
                if ($(this).val() !== "" && selectedItems.includes($(this).val()) && $(this).val() !== currentValue) {
                    $(this).hide(); // Hide already selected items
                } else {
                    $(this).show(); // Show unselected items
                }
            });
        });
    }

    
    // Trigger update when a milk item is selected
    $(document).on("change", "select[name='production_milk_id[]']", function () {
        updateMilkItemOptions();
        updateAddButtonVisibility();

       /**this experiment code */ updateStockQuantity(this);
    });

    checkForErrors();

// Check for errors whenever an input or select field changes
$(document).on("input", "#milkTable tbody input, #milkTable tbody select", function () {
    checkForErrors();
});
});



</script>


<script>



</script>

@endsection
