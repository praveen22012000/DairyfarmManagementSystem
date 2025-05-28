@extends('layouts.admin.master')

@section('content')

<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Salary Details</h1>

    <form action="{{ route('salary.store') }}" method="POST" class="space-y-8">
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
                <!-- this is used to list the animal types-->
            <div class="form-group">
                <label for="role_id">Role</label>
           
                    <!-- this is used to list the animal types-->
                    <select name="role_id" id="role_id" class="form-control" >
                        <option value="">Select Role</option>
             
                                @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name}}</option>
                                @endforeach
                    </select>
                @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


           

            <div>
                <label for="base_salary">Base Salary(Rs.)</label>
                <input type="text" name="base_salary" id="base_salary"  class="form-control rounded"  value="">
                @error('base_salary') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


                  
            <div class="form-group">
                <label for="effective_from">Effective From</label>
                <input type="date" name="effective_from" class="form-control rounded" id="effective_from">
                @error('effective_from') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="effective_to">Effective To</label>
                <input type="date" name="effective_to" class="form-control rounded" id="effective_to">
                @error('effective_to') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


        </div>

        <br>

       

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" class="btn btn-success mt-3">Save</button>
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
