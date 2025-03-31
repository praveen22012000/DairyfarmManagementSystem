@extends('layouts.admin.master')

@section('content')
<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Dispose Vaccine Items</h1>

    <form action="{{ route('dispose_vaccine_items.store') }}" method="POST" class="space-y-8">
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

        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Dispose Date</label>
                <input type="date" name="dispose_date" id="date" class="form-control rounded" value="{{old('dispose_date')}}" >
                @error('dispose_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Dispose Time</label>
                <input type="time" name="dispose_time" id="time" class="form-control rounded" value="{{old('dispose_time')}}" >
                @error('dispose_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        </div>

        <br>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table id="disposeVaccineTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Feed Item</th>
                        <th class="border-b px-6 py-4 text-left">Stock Quantity (SQ)</th>
                        <th class="border-b px-6 py-4 text-left">Dispose Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Reason</th>
                        <th class="border-b px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $oldPurchaseVaccineItemIds = old('purchase_vaccine_item_id', []);
                        $oldDisposedQuantities = old('dispose_quantity', []);
                        $oldReasonForDisposes = old('reason_for_dispose', []);
                        $rowCount = max(count($oldPurchaseVaccineItemIds), 1);
                    @endphp

                    @for ($i = 0; $i < $rowCount; $i++)
                    <tr  class="milk-row">
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="purchase_vaccine_item_id[]" id="purchase_vaccine_item_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Select the Vaccine Item</option>
                                @foreach($purchaseVaccineItems as $purchaseVaccineItem)
                                    <option value="{{$purchaseVaccineItem->id}}" 
                                    {{ (isset($oldPurchaseVaccineItemIds[$i]) && $oldPurchaseVaccineItemIds[$i] == $purchaseVaccineItem->id) ? 'selected' : '' }}

                                       data-stock_quantity="{{ $purchaseVaccineItem->stock_quantity }}">
                                        {{ $purchaseVaccineItem->id.' | '.$purchaseVaccineItem->vaccine->vaccine_name.' | '.$purchaseVaccineItem->manufacture_date }}
                                    </option>
                                @endforeach 
                            
                            </select>
                            @error("purchase_vaccine_item_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="form-control" name="quantity[]" value="{{ isset($oldPurchaseVaccineItemIds[$i]) ? ($purchaseVaccineItems->firstWhere('id', $oldPurchaseVaccineItemIds[$i])?->stock_quantity ?? '') : '' }}" style="width: 80px;" readonly>
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="dispose_quantity[]" value="{{ $oldDisposedQuantities[$i] ?? '' }}" class="form-control"  style="width: 80px;">
                            @error("dispose_quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                        <input type="text" name="reason_for_dispose[]" class="form-control"  style="width:300px;" value="{{$oldReasonForDisposes[$i] ?? ''}}">
                        @error("reason_for_dispose.$i") <span class="text-danger">{{ $message }}</span> @enderror
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
     $("select[name='purchase_vaccine_item_id[]']").each(function () {
        let selectedOption = $(this).find(":selected");
        let stockQuantity = selectedOption.data("stock_quantity");

        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });

    // Update stock quantity dynamically on selection change
    $(document).on("change", "select[name='purchase_vaccine_item_id[]']", function () {
        let selectedOption = $(this).find(":selected");
        let stockQuantity = selectedOption.data("stock_quantity");

        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });


    // Function to check for validation errors in the table rows
    function checkForErrors() {
        let hasErrors = false;

        // Loop through all rows and check for error messages
        $("#disposeVaccineTable tbody tr").each(function () {
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
        let totalMilkItems = $("#disposeVaccineTable tbody tr:first").find("select[name='purchase_vaccine_item_id[]'] option").length - 1; // Exclude the default option
        let totalRows = $("#disposeVaccineTable tbody tr").length;


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
        let table = $("#disposeVaccineTable tbody");
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
        let table = $("#disposeVaccineTable tbody");

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
        $("select[name='purchase_vaccine_item_id[]']").each(function () {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedItems.push(selectedValue);
            }
        });

        

        //It iterates over all <select> dropdowns that have the name production_milk_id[] and hides any option 
        // --that has already been selected in another dropdown
        $("select[name='purchase_vaccine_item_id[]']").each(function () {
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
    $(document).on("change", "select[name='purchase_vaccine_item_id[]']", function () {
        updateMilkItemOptions();
        updateAddButtonVisibility();

      
    });

    checkForErrors();

// Check for errors whenever an input or select field changes
$(document).on("input", "#disposeVaccineTable tbody input, #disposeFeedTable tbody select", function () {
    checkForErrors();
});
});




</script>


@endsection
