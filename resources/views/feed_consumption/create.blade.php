@extends('layouts.admin.master')

@section('content')
<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Feed Consumption Details</h1>

    <form action="{{ route('feed_consume_items.store') }}" method="POST" class="space-y-8">
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

            <div class="form-group">
                <label for="animal_id">Animal Name</label>
                    <select name="animal_id" id="animal_id" class="form-control" >
                        <option value="">Select Animal</option>
                            @foreach($animals as $animal)
                        <option value="{{ $animal->id }}"
                        {{ old('animal_id') == $animal->id ? 'selected' : '' }}
                        >{{ $animal->animal_name }}</option>
                            @endforeach
                    </select>
                @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" id="date" class="form-control rounded" value="{{old('date')}}" >
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <input type="time" name="time" id="time" class="form-control rounded" value="{{old('time')}}" >
                @error('time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        </div>

        <br>

        <!-- Milk Production Table -->
        <div class="overflow-x-auto">
            <table id="disposeVaccineTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Feed Name</th>
                        <th class="border-b px-6 py-4 text-left">Stock Quantity (SQ)</th>
                        <th class="border-b px-6 py-4 text-left">Consume Quantity</th>
                        <th class="border-b px-6 py-4 text-left">notes</th>
                        <th class="border-b px-6 py-4 text-left">Feed Item</th>
                        <th class="border-b px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $oldFeedIds = old('feed_id', []);
                        $oldPurchaseFeedItemIds = old('purchase_feed_item_id', []);
                  
                        $oldConsumQuantities = old('consume_quantity', []);
                        $oldNotes = old('notes', []);
                        $rowCount = max(count($oldFeedIds), 1);
                    @endphp

                    @for ($i = 0; $i < $rowCount; $i++)
                    <tr  class="milk-row">

                    
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="feed_id[]" id="feed_id[]" class="feed-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Feed</option>
                                    @foreach($feeds as $feed)
                                    <option value="{{$feed->id}}"
                                            {{ (isset($oldFeedIds[$i]) && $oldFeedIds[$i] == $feed->id) ? 'selected' : '' }}
                                            >
                                                    {{$feed->feed_name}}
                                    </option>
                                    @endforeach 
                            </select>
                                @error("feed_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="form-control" name="quantity[]" value="" readonly>
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="consumed_quantity[]" value="{{ old('consumed_quantity.' . $i) }}" class="form-control"  style="width: 80px;">
                            @error("consumed_quantity.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4">
                        <input type="text" name="notes[]" class="form-control"   style="width:150px;" value="{{ old('notes.' . $i) }}">
                        @error("notes.$i") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                                <select name="purchase_feed_item_id[]" id="purchase_feed_item_id[]" class="purchase-item-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" disabled>
                                        <option value="">Select the Purchase Feed Item</option>
                                            @foreach($purchaseFeedItems as $item)
                                                    <option value="{{ $item->id }}" 
                                                        data-feed-id="{{ $item->feed_id }}"
                                                        data-stock-quantity="{{ $item->stock_quantity }}"
                                                            {{ (isset($oldPurchaseFeedItemIds[$i]) && $oldPurchaseFeedItemIds[$i] == $item->id) ? 'selected' : '' }}>
                                                                    {{ $item->id.'|'.$item->feed->feed_name.'|'.$item->manufacture_date }}
                                                    </option>
                                            @endforeach
                                </select>
                                    @error("purchase_feed_item_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
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
            <button type="submit" class="btn btn-success mt-3">Save Feed Consumption Record</button>
        </div>
    </form>
</div>


@endsection

@section('js')

<script>
   
   $(document).ready(function () {
    let today = new Date().toISOString().split("T")[0];
    $("#date").attr("max", today);

    // Function to update stock quantity when purchase item is selected
    function updateStockQuantity(selectElement) { //Defines a function named updateStockQuantity.//It accepts one parameter, selectElement, which represents the <select> dropdown element that was interacted with.

        let selectedOption = $(selectElement).find(":selected");//.find(":selected") searches for the currently selected <option> inside the <select> dropdown
        let stockQuantity = selectedOption.data("stock-quantity"); //Retrieves the stock quantity from the data-stock-quantity attribute of the selected <option>.
        $(selectElement).closest("tr").find("input[name='quantity[]']").val(stockQuantity || '')//Finds the correct row (tr);//Finds the quantity input inside that row//Updates the input field with the selected stock quantity
    }



    // Initialize stock quantities for existing rows on page load
    //This code loops through all dropdowns (<select>) that have the class .purchase-item-select.
    //If a dropdown already has a selected value, it calls the updateStockQuantity function for that dropdown
    $(".purchase-item-select").each(function() {
        if ($(this).val()) {
            updateStockQuantity(this);
        }
    });

    // Update stock quantity when purchase item selection changes
    $(document).on("change", ".purchase-item-select", function() {
        updateStockQuantity(this);
    });

    

    // Enable/disable purchase feed item dropdown based on feed selection
    $(document).on("change", ".feed-select", function() { //Listens for a change event on any element with class "feed-select"
        let row = $(this).closest("tr");//"Find the table row where this dropdown is located
        let purchaseItemSelect = row.find(".purchase-item-select");//Within that row, find the second dropdown for selecting items related to the feed
        let selectedFeedId = $(this).val();//Store the ID of the selected feed
        
        if (selectedFeedId) {//Checks if the user selected a valid feed
           
            purchaseItemSelect.prop("disabled", false);//Turn on the second dropdown so the user can use it
            //Enables the purchase item dropdown by removing the disabled attribute.
            
            // Filter options to show only items for the selected feed
            purchaseItemSelect.find("option").each(function() {//Go through each option in the purchase item dropdown
                let optionFeedId = $(this).data("feed-id");
                if (optionFeedId == selectedFeedId || $(this).val() === "") {//Only show items that belong to the selected feed.
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Reset the selection and clear stock quantity
            purchaseItemSelect.val("");
            row.find("input[name='quantity[]']").val("");
        } else {
            // Disable the purchase item dropdown if no feed is selected
            purchaseItemSelect.prop("disabled", true);
            purchaseItemSelect.val("");
            row.find("input[name='quantity[]']").val("");
        }
    });

    // Initialize existing rows
    $(".feed-select").each(function() {
        if ($(this).val()) {
            $(this).trigger("change");
        }
    });


    

    // Add new row
    $("#addRow").click(function () {
        let table = $("#disposeVaccineTable tbody");
        let newRow = table.find("tr:first").clone();
        
        // Clear values and disable purchase item dropdown
        newRow.find("input").val("");
        newRow.find(".feed-select").val("");
        newRow.find(".purchase-item-select").val("").prop("disabled", true);
        newRow.find("select").prop("selectedIndex", 0);
        
        table.append(newRow);
        updateMilkItemOptions();
        updateAddButtonVisibility();
    });

    // Remove row
    $(document).on("click", ".remove-row", function () {
        let table = $("#disposeVaccineTable tbody");
        if (table.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateMilkItemOptions();
            updateAddButtonVisibility();
        } else {
            alert("At least one row is required.");
        }
    });

    // Function to check for validation errors
    function checkForErrors() {
        let hasErrors = false;
        $("#disposeVaccineTable tbody tr").each(function () {
            if ($(this).find(".text-danger").length > 0) {
                hasErrors = true;
                return false;
            }
        });
        $("#addRow").toggle(!hasErrors);
        if (!hasErrors) updateAddButtonVisibility();
    }

    function updateAddButtonVisibility() {
        let totalMilkItems = $("#disposeVaccineTable tbody tr:first").find("select[name='feed_id[]'] option").length - 1;
        let totalRows = $("#disposeVaccineTable tbody tr").length;
        $("#addRow").toggle(totalRows < totalMilkItems);
    }

    function updateMilkItemOptions() {
        let selectedItems = [];
        $("select[name='feed_id[]']").each(function () {
            let selectedValue = $(this).val();
            if (selectedValue) selectedItems.push(selectedValue);
        });
        
        $("select[name='feed_id[]']").each(function () {
            let currentValue = $(this).val();
            $(this).find("option").each(function () {
                $(this).toggle(!($(this).val() && selectedItems.includes($(this).val()) && $(this).val() !== currentValue));
            });
        });
    }

    // Event handlers
    $(document).on("change", "select[name='feed_id[]']", function () {
        updateMilkItemOptions();
        updateAddButtonVisibility();
    });

    $(document).on("input", "#disposeVaccineTable tbody input, select", function () {
        checkForErrors();
    });

    // Initial setup
    checkForErrors();
});




</script>


@endsection
