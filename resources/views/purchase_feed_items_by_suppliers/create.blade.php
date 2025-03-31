@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Purchase Feed Items</h1>

    <form action="{{route('purchase_feed_items.store')}}" method="POST" class="space-y-8">
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

        <!-- Date, Time, and Entered By Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
        <div class="mb-4">
                <label for="supplier_id" class="block text-gray-700 font-medium" >Suppliers</label>
                    <select name="supplier_id" id="supplier_id"  class="form-control">
                    <option value="">Select the Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                            >{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

            <div>
                <label for="purchase_date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <br>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control rounded">
                @error('purchase_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
           
        
        </div>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table  class="table" id="purchaseTable">
                <thead  class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Feed</th>
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">UnitPrice</th>
                        <th class="border-b px-6 py-4 text-left">Manufacture Date</th>
                        <th class="border-b px-6 py-4 text-left">ExpiryDate</th>
                        <th>Actions</th>


                    </tr>
                </thead>
              
                <tbody>
                @php
                        $oldFeedIds = old('feed_id', []);
                        $oldPurchasedQuantities = old('purchase_quantity', []);

                        $oldUnitPrices=old('unit_price',[]);
                        $oldManufactureDates=old('manufacture_date',[]);
                        $oldExpireDates=old('expire_date',[]);

                        $rowCount = max(count($oldFeedIds), 1);
                @endphp

                @for ($i = 0; $i < $rowCount; $i++)
                <tr class="purchase-row">
                   
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                                <select name="feed_id[]" id="feed_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Feed</option>
                                            @foreach($feeds as $feed)
                                    <option value="{{$feed->id}}"
                                    {{ (isset($oldFeedIds[$i]) && $oldFeedIds[$i] == $feed->id) ? 'selected' : '' }}
                                    >{{ $feed->feed_name }}</option>
                                            @endforeach
                                </select>
                            @error("feed_id.$i") <span class="text-danger">{{ $message }}</span> @enderror

                        </td>

                        
                        <td>
                            <input type="number" class="form-control" name="purchase_quantity[]" value="{{  $oldPurchasedQuantities[$i] ?? '' }}" style="width: 100px;">
                            @error("purchase_quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror  
                        </td>

                        <td>
                            <input type="number" class="form-control" name="unit_price[]" value="{{ $oldUnitPrices[$i] ?? ''}}"  style="width: 100px;">
                            @error("unit_price.$i") <span class="text-danger">{{ $message }}</span> @enderror  
                        </td>



                   
                        <td>
                            <input type="date" name="manufacture_date[]" value="{{ $oldManufactureDates[$i] ?? ''}}"  class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error("manufacture_date.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

           
                        <td>
                            <input type="date" name="expire_date[]" value="{{ $oldExpireDates[$i] ?? ''}}"  class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error("expire_date.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>



                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>

                        
                       
                </tr>
              @endfor                                                                                                          
              
            </tbody>
      


            </table>
        </div>

        <div class="flex justify-center mt-3">
            <button type="button" id="addRow" class="btn btn-primary">Add</button>
        </div>
        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" class="btn btn-success mt-3">Save Purchase Record</button>
        </div>
    </form>
</div>


@endsection

      
@section('js')



<script>
$(document).ready(function () {
    // Function to check for validation errors in the table rows
    function checkForErrors() {
        let hasErrors = false;
        $("#purchaseTable tbody tr").each(function () {
            if ($(this).find(".text-danger").length > 0) {
                hasErrors = true;
                return false; // Exit the loop if an error is found
            }
        });
        if (hasErrors) {
            $("#addRow").hide();
        } else {
            updateAddButtonVisibility(); // Otherwise, update visibility based on row count
        }
    }


    
    // Function to update the "Add" button visibility based on the row count
    function updateAddButtonVisibility() {
        let totalFeedItems = $("#purchaseTable tbody tr:first").find("select[name='feed_id[]'] option").length - 1; // Exclude the default option
        let totalRows = $("#purchaseTable tbody tr").length;//
        if (totalRows >= totalFeedItems) {
            $("#addRow").hide();
        } else {
            $("#addRow").show();
        }
    }

    // Add new row functionality
    $("#addRow").click(function () {
        let table = $("#purchaseTable tbody");// Selects the <tbody> of the table where rows are added.
        let newRow = table.find("tr:first").clone();// Finds the first row (<tr>) inside <tbody>. Finds the first row (<tr>) inside <tbody>
        newRow.find("input").val(""); // Clear input values in the new row
        newRow.find("select").prop("selectedIndex", 0); // Reset dropdowns -- Resets the dropdown selection to the first option.
        table.append(newRow);//Adds the newly cloned row at the bottom of the table.
        updateFeedItemOptions();
        updateAddButtonVisibility();
    });

    // Remove a row when the "Remove" button is clicked
    $(document).on("click", ".remove-row", function () {
        let table = $("#purchaseTable tbody");//Selects the <tbody> inside the #purchaseTable where rows are present.
        if (table.find("tr").length > 1) {// Counts how many <tr> (table rows) are inside <tbody>.If there is more than one row, the function proceeds to remove the row.
            $(this).closest("tr").remove();//Finds the closest <tr> (row) that contains this button.Deletes that row from the table.
            updateFeedItemOptions();//Calls the updateFeedItemOptions() function to ensure that the removed feed can now be selected in other rows.
            updateAddButtonVisibility();//If a row is removed, the "Add" button may become visible again.
        } else {
            alert("At least one row is required.");//If only one row remains, an alert stops the deletion.
        }
    });

    // Update feed item options to ensure each row has unique product selection
    function updateFeedItemOptions() {
        // Get all selected values except empty ones
        let selectedItems = [];//Creates an empty array selectedItems to store all selected feed items.
        $("select[name='feed_id[]']").each(function () {//Loops through each <select> element with the name feed_id[].
            let selectedValue = $(this).val();//Gets the selected value of each dropdown ($(this).val()).
            if (selectedValue) {//If the value is not empty, it is added to the selectedItems array.
                selectedItems.push(selectedValue);//If the value is not empty, it is added to the selectedItems array.
            }
        });
        
        // Update each select element
        $("select[name='feed_id[]']").each(function () {//Loops through each dropdown (<select>) again.
            let currentValue = $(this).val();//Stores the currently selected value for that row (currentValue).
            $(this).find("option").each(function () {// Finds all <option> elements inside the <select>.
                let optionValue = $(this).val();//Stores the option’s value in optionValue.
                // Show the option if:
                // 1. It's the empty option
                // 2. It's the currently selected option for this row
                // 3. It hasn't been selected in any other row
                if (optionValue === "" || 
                    optionValue === currentValue || 
                    !selectedItems.includes(optionValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    }

    // Trigger update when a feed item is selected
    $(document).on("change", "select[name='feed_id[]']", function () {//Listens for changes in any <select> element with name='feed_id[]' (i.e., feed item dropdowns).

        updateFeedItemOptions();//updateFeedItemOptions() is called to ensure unique product selection
        updateAddButtonVisibility();//updateAddButtonVisibility() is called to hide or show the "Add" button based on the number of selected feed items.
    });

    // Initialize the page
    checkForErrors();//Checks if there are any errors when the page loads. If errors exist, it hides the "Add" button.
    updateFeedItemOptions();// Ensures that each row has unique feed selection from the start.

    //Listens for changes in input fields and dropdowns inside #purchaseTable tbody
    $(document).on("input", "#purchaseTable tbody input,#purchaseTable tbody select", function () {
        checkForErrors();//Looks for errors in the table.//If an error exists, it hides the "Add" button (#addRow).

    });
});
</script>
@endsection
