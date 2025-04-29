@extends('layouts.admin.master')

@section('content')
<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Retailor Orders</h1>

    <form action="{{ route('retailor_order_items.store') }}" method="POST" class="space-y-8">
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

        <br>
            <div>
                <label for="delivery_address" class="block text-lg font-medium text-gray-700 mb-2">Delivery Address</label>
                <input type="text" name="delivery_address" id="delivery_address" placeholder="Enter your delivery address" class="form-control rounded" value="{{old('delivery_address')}}" >
                @error('delivery_address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            <div>
                <label for="total_amount" class="block text-lg font-medium text-gray-700 mb-2">Total Amount</label>
                <input type="text" name="total_amount" id="total_amount"  class="form-control rounded" readonly value="">
                @error('total_amount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <br>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table id="milkTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">MilkProduct</th>
                      
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Unit Price</th>
                        <th class="border-b px-6 py-4 text-left">Subtotal</th>
                        <th class="border-b px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $oldProductIds = old('product_id', []);
                        $oldOrderedQuantities = old('ordered_quantity', []);
                    
                        $rowCount = max(count($oldProductIds), 1);
                    @endphp

                    @for ($i = 0; $i < $rowCount; $i++)
                    <tr  class="milk-row">
                    <td class="border-t px-6 py-4 text-left text-gray-800">
                    <select name="product_id[]" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Select the Product</option>
                            @foreach($milk_products as $milk_product)
                                <option value="{{$milk_product->id}}" 
                                    {{ (isset($oldProductIds[$i]) && $oldProductIds[$i] == $milk_product->id) ? 'selected' : '' }}
                                        data-unit_price="{{ $milk_product->unit_price }}"
                                        data-stock_quantity="{{ $milk_product->manufacturer_product->sum('stock_quantity') }}">
                                            {{ $milk_product->product_name  }}
                                </option>
                            @endforeach
                    </select>
                    @error("product_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                      
                        <td class="border-t px-6 py-4">
                            <input type="number" name="ordered_quantity[]" class="form-control rounded" value="{{ $oldOrderedQuantities[$i] ?? '' }}">
                            @error("ordered_quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="unit_price[]" class="form-control rounded" readonly value="">
                            @error("unit_price.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="subtotal[]" class="form-control subtotal" readonly>
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
            <button type="submit" class="btn btn-success mt-3">Save Order Record</button>
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
    $("select[name='product_id[]']").each(function () {
        let selectedOption = $(this).find(":selected");
        let unitPrice = selectedOption.data("unit_price");
        let stockQuantity = selectedOption.data("stock_quantity");

        $(this).closest("tr").find("input[name='unit_price[]']").val(unitPrice);
        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });

    // Update stock quantity and unit price dynamically on selection change
    $(document).on("change", "select[name='product_id[]']", function () {
        let selectedOption = $(this).find(":selected");
        let unitPrice = selectedOption.data("unit_price");
        let stockQuantity = selectedOption.data("stock_quantity");

        $(this).closest("tr").find("input[name='unit_price[]']").val(unitPrice);
        $(this).closest("tr").find("input[name='quantity[]']").val(stockQuantity);
    });

    // Calculate subtotal for a single row
    function updateRowSubtotal(row) 
    {
        const qty = parseFloat(row.find('input[name="ordered_quantity[]"]').val()) || 0;
        const price = parseFloat(row.find('input[name="unit_price[]"]').val()) || 0;
        const subtotal = qty * price;
    
        row.find('input[name="subtotal[]"]').val(subtotal.toFixed(2));
        return subtotal;
    }

    // Update all subtotals + grand total
    function updateAllTotals() 
    {
        let grandTotal = 0;
    
        $('.milk-row').each(function() {
            grandTotal += updateRowSubtotal($(this));
        });
    
        $('#grand_total').text(grandTotal.toFixed(2));
        $('#total_amount').val(grandTotal.toFixed(2)); // For form submission
    }

// Trigger calculations on input changes
$(document).on('input', 'input[name="ordered_quantity[]"], input[name="unit_price[]"]', function() {
    updateAllTotals();
});

// Initialize on page load
$(document).ready(function() {
    updateAllTotals();
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
        let totalMilkItems = $("#milkTable tbody tr:first").find("select[name='product_id[]'] option").length - 1; // Exclude the default option
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
        $("select[name='product_id[]']").each(function () {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedItems.push(selectedValue);
            }
        });

        

        //It iterates over all <select> dropdowns that have the name production_milk_id[] and hides any option 
        // --that has already been selected in another dropdown
        $("select[name='product_id[]']").each(function () {
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
    $(document).on("change", "select[name='product_id[]']", function () {
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
