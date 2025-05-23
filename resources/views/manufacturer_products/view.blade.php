@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Manufacturing Milk Products</h1>

    <form action="" method="POST" class="space-y-8">
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
                <input type="date" name="date" id="date" class="form-control rounded" value="{{$manufacturerProduct->manufacturer->date}}">
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <br>
                <input type="time" name="time" id="time"  class="form-control rounded" value="{{$manufacturerProduct->manufacturer->time}}">
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>

            <div>
                <label for="enter_by" class="block text-lg font-medium text-gray-700 mb-2">Entered By</label>
                <br>
                <input type="text" name="enter_by" id="enter_by" placeholder="Enter your name" class="form-control rounded" value="{{$manufacturerProduct->manufacturer->enter_by}}">
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
                     
                       


                    </tr>
                </thead>
              
               
                <tr clas="milk-row">
                    <!-- ID -->
                        <td>
                                <select name="product_id" id="product_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Product</option>
                                            @foreach($milkProducts as $milkProduct)
                                    <option value="{{$milkProduct->id}}"
                                   {{$manufacturerProduct->product_id==$milkProduct->id ? 'selected': ''}}
                                    >{{ $milkProduct->product_name }}</option>
                                            @endforeach
                                </select>
                            @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- MilkDetails (Smaller Width) -->
                        <td>
                            <input type="number" class="form-control" name="quantity" value="{{$manufacturerProduct-> quantity}}"  style="width: 100px;">
                            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror  
                        </td>

                        <!-- Stock Quantity (Smaller Width) -->
                        <td>
                            <input type="date" name="manufacture_date" value="{{$manufacturerProduct-> manufacture_date}}" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('manufacture_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <!-- Quantity (Smaller Width) -->
                        <td>
                            <input type="date" name="expire_date" value="{{$manufacturerProduct-> expire_date}}" class="border border-gray-400 rounded-lg px-2 py-1 w-28 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('expire_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td>
                            <select name="user_id" id="user_id" class="border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Select the Farm Labor</option>
                                        @foreach($farm_labors as $farm_labor)
                                    <option value="{{$farm_labor->id}}"
                                    {{$manufacturerProduct->user_id==$farm_labor->id ? 'selected': ''}}
                                    >{{$farm_labor->name}}</option>
                                        @endforeach
                            </select>
                            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>


                      
                        
                       
                </tr>
              
     
      


            </table>
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

   
    // Check for errors on page load
    checkForErrors();

    // Check for errors whenever an input or select field changes
    $(document).on("input", "#manufactureTable tbody input, #manufactureTable tbody select", function () {
        checkForErrors();
    });
});
</script>
@endsection
