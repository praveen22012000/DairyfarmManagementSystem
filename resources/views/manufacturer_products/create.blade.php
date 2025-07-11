@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Manufacturing Milk Products</h1>

    <form action="{{route('manufacture_product.store')}}" method="POST" class="space-y-8">
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
            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <br>
                <input type="date" name="date" id="date" value="{{ old('date') }}" class="form-control rounded"
                  >
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <br>
                <input type="time" name="time" id="time" value="{{ old('time') }}" class="form-control rounded">
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>

            <div>
                <label for="enter_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <br>
                <input type="text" name="enter_by" id="enter_by" value="{{ old('enter_by') }}" placeholder="Enter your name" class="form-control rounded"
                   >
                @error('enter_by') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
        </div>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table  class="table" id="manufactureTable">
                <thead  class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Product</th>
                        <th class="border-b px-6 py-4 text-left">Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Manufacture Date</th>
                        <th class="border-b px-6 py-4 text-left">ExpiryDate</th>
                        <th class="border-b px-6 py-4 text-left">Manufactured By</th>
                        <th  class="border-b px-6 py-4 text-left">Actions</th>
                       


                    </tr>
                </thead>
              
                @php
                        $oldMilkProductIds = old('product_id', []);
                        $oldManufacturedQuantities = old('quantity', []);

                        $oldManufactureDate=old('manufacture_date',[]);
                        $oldExpireDate=old('expire_date',[]);
                        $oldUser=old('user_id',[]);

                      
                        $rowCount = max(count($oldMilkProductIds), 1);
                    @endphp

                    @for ($i = 0; $i < $rowCount; $i++)
                <tr clas="milk-row">
                    <!-- ID -->
                        <td>
                                <select name="product_id[]" id="product_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Product</option>
                                            @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id}}"
                                    {{ (isset($oldMilkProductIds[$i]) && $oldMilkProductIds[$i] == $milkProduct->id) ? 'selected' : '' }}
                                    >{{ $milkProduct->product_name }}</option>
                                            @endforeach
                                </select>
                            @error("product_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- MilkDetails (Smaller Width) -->
                        <td>
                            <input type="number" class="form-control" name="quantity[]" value="{{ $oldManufacturedQuantities[$i] ?? '' }}"  style="width: 100px;">
                            @error("quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror  
                        </td>

                        <!-- Stock Quantity (Smaller Width) -->
                        <td>
                            <input type="date" name="manufacture_date[]" value="{{ $oldManufactureDate[$i] ?? '' }}" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error("manufacture_date.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- Quantity (Smaller Width) -->
                        <td>
                            <input type="date" name="expire_date[]" value="{{ $oldExpireDate[$i] ?? '' }}" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error("expire_date.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                            <select name="user_id[]" id="user_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Farm Labor</option>
                                        @foreach($farm_labors as $farm_labor)
                                    <option value="{{$farm_labor->id}}"
                                    {{ (isset($oldUser[$i]) && $oldUser[$i] == $farm_labor->id) ? 'selected' : '' }}
                                    >{{$farm_labor->name}}</option>
                                        @endforeach
                            </select>
                            @error("user_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>


                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>

                        
                       
                </tr>
                @endfor
              
     
      


            </table>
        </div>

        <div class="flex justify-center mt-3">
            <button type="button" id="addRow" class="btn btn-primary">Add</button>
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
$(document).ready(function () {
    // Function to check for validation errors in the table rows
    function checkForErrors() {
        let hasErrors = false;
        $("#manufactureTable tbody tr").each(function () {
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
        let totalProductItems = $("#manufactureTable tbody tr:first").find("select[name='product_id[]'] option").length - 1; // Exclude the default option
        let totalRows = $("#manufactureTable tbody tr").length;
        if (totalRows >= totalProductItems) {
            $("#addRow").hide();
        } else {
            $("#addRow").show();
        }
    }

    // Add new row functionality
    $("#addRow").click(function () {
        let table = $("#manufactureTable tbody");
        let newRow = table.find("tr:first").clone();
        newRow.find("input").val(""); // Clear input values in the new row
        newRow.find("select").prop("selectedIndex", 0); // Reset dropdowns
        table.append(newRow);
        updateMilkItemOptions();
        updateAddButtonVisibility();
    });

    // Remove a row when the "Remove" button is clicked
    $(document).on("click", ".remove-row", function () {
        let table = $("#manufactureTable tbody");
        if (table.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateMilkItemOptions();
            updateAddButtonVisibility();
        } else {
            alert("At least one row is required.");
        }
    });

    // Update milk item options to ensure each row has a unique product selection
    function updateMilkItemOptions() {
        let selectedItems = [];
        $("select[name='product_id[]']").each(function () {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedItems.push(selectedValue);
            }
        });
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

       
    });

   
    // Check for errors on page load
    checkForErrors();

    // Check for errors whenever an input or select field changes
    $(document).on("input", "#manufactureTable tbody input, #manufactureTable tbody select", function () {
        checkForErrors();
    });
});
</script>
@endsection
