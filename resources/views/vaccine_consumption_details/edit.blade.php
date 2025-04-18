@extends('layouts.admin.master')

@section('content')
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Vaccine Consumption Details</h1>

    <form action="{{route('vaccine_consume_items.update',$vaccineconsumeitem->id)}}" method="POST" class="space-y-8">
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
                <label for="vaccination_date" class="block text-lg font-medium text-gray-700 mb-2">Vaccination Date</label>
                <input type="date" name="vaccination_date" id="vaccination_date" class="form-control rounded" value="{{$vaccineconsumeitem->vaccine_consume_detail->vaccination_date}}">
                @error('vaccination_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
            <div class="form-group">
                <label for="appointment_id" class="block text-lg font-medium text-gray-700 mb-2">Veterinarian Appointment</label>
                <select name="appointment_id" id="appointment_id" class="form-control">
                    <option value="">Select Appointment</option>
                    @foreach($appointments as $appointment)   
                        <option value="{{ $appointment->id }}" {{ $vaccineconsumeitem->vaccine_consume_detail->appointment_id == $appointment->id ? 'selected' : '' }}>
                            {{ $appointment->id .'|'. $appointment->user->name .'|'. $appointment->appointment_date }}
                        </option>
                    @endforeach
                </select>
                @error('appointment_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Vaccine Consumption Table -->
        <div class="overflow-x-auto">
            <table id="vaccineTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="border-b px-6 py-4 text-left">Animal</th>
                        <th class="border-b px-6 py-4 text-left">Vaccine</th>
                        <th class="border-b px-6 py-4 text-left">Vaccine Items</th>
                        <th class="border-b px-6 py-4 text-left">Stock Quantity</th>
                        <th class="border-b px-6 py-4 text-left">Consumed Quantity</th>
                     
                    </tr>
                </thead>
                <tbody>
                  

                    
                    <tr class="milk-row">
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="animal_id" class="animal-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Animal</option>
                                @foreach($animals as $animal)
                                    <option value="{{ $animal->id }}"
                                        {{ $vaccineconsumeitem->animal_id == $animal->id ? 'selected' : '' }}>
                                        {{ $animal->animal_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="vaccine_id" class="vaccine-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Vaccine</option>
                                @foreach($vaccinations as $vaccination)
                                    <option value="{{ $vaccination->id }}"
                                        {{ $vaccineconsumeitem->vaccine_id == $vaccination->id ? 'selected' : '' }}>
                                        {{ $vaccination->vaccine_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vaccine_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="vaccination_item_id" class="purchase-item-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                               >
                                <option value="">Select the Vaccine Items</option>
                                @foreach($vaccination_items as $vaccination_item)
                                    <option value="{{ $vaccination_item->id }}"
                                        data-vaccine-id="{{ $vaccination_item->vaccine_id }}"
                                        data-stock-quantity="{{ $vaccination_item->stock_quantity }}"
                                        {{ $vaccineconsumeitem->vaccination_item_id == $vaccination_item->id ? 'selected' : '' }}>
                                        {{ $vaccination_item->id.'|'. $vaccination_item->vaccine->vaccine_name.'|'. $vaccination_item->expire_date }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vaccination_item_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="stock-quantity form-control" name="quantity[]" readonly style="width: 90px;" 
                                   value="">
                        </td>

                        <td class="border-t px-6 py-4">
                            <input type="text" name="consumed_quantity" class="form-control rounded" 
                                   value="{{ $vaccineconsumeitem->consumed_quantity }}">
                            @error('consumed_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                     
                    </tr>
                   
                </tbody>
            </table>
        </div>

        <div class="flex justify-center">
            <button type="submit" class="btn btn-success mt-3">Update</button>
        </div>

    </form>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    let today = new Date().toISOString().split("T")[0];
    $("#vaccination_date").attr("max", today);

    // Function to update stock quantity when purchase item is selected
    function updateStockQuantity(selectElement) {
        let selectedOption = $(selectElement).find(":selected");
        let stockQuantity = selectedOption.data("stock-quantity");
        $(selectElement).closest("tr").find(".stock-quantity").val(stockQuantity || '');
    }

    // Initialize existing rows on page load
    function initializeRows() {
        $(".vaccine-select").each(function() {
            let row = $(this).closest("tr");
            let selectedVaccineId = $(this).val();
            let purchaseItemSelect = row.find(".purchase-item-select");
            let selectedItemId = purchaseItemSelect.val();
            
            if (selectedVaccineId) {
                purchaseItemSelect.prop("disabled", false);
                
                // Filter options to show only items for the selected vaccine
                purchaseItemSelect.find("option").each(function() {
                    let optionVaccineId = $(this).data("vaccine-id");
                    if (optionVaccineId == selectedVaccineId || $(this).val() === "") {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                
                // If there's a selected item, update the stock quantity
                if (selectedItemId) {
                    updateStockQuantity(purchaseItemSelect);
                }
            }
        });
    }

    // Call initialization on page load
    initializeRows();

    // Update stock quantity when purchase item selection changes
    $(document).on("change", ".purchase-item-select", function() {
        updateStockQuantity(this);
    });

    // Enable/disable vaccine item dropdown based on vaccine selection
    $(document).on("change", ".vaccine-select", function() {
        let row = $(this).closest("tr");
        let purchaseItemSelect = row.find(".purchase-item-select");
        let selectedVaccineId = $(this).val();
        let oldSelectedItemId = purchaseItemSelect.val();
        
        if (selectedVaccineId) {
            purchaseItemSelect.prop("disabled", false);
            
            // Filter options to show only items for the selected vaccine
            purchaseItemSelect.find("option").each(function() {
                let optionVaccineId = $(this).data("vaccine-id");
                if (optionVaccineId == selectedVaccineId || $(this).val() === "") {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // If there was a previously selected item that belongs to the new vaccine, keep it selected
            if (oldSelectedItemId) {
                let selectedOption = purchaseItemSelect.find("option[value='" + oldSelectedItemId + "']");
                if (selectedOption.data("vaccine-id") == selectedVaccineId) {
                    purchaseItemSelect.val(oldSelectedItemId);
                    updateStockQuantity(purchaseItemSelect);
                    return;
                }
            }
            
            // Otherwise reset the selection
            purchaseItemSelect.val("");
            row.find(".stock-quantity").val("");
        } else {
            // Disable the purchase item dropdown if no vaccine is selected
            purchaseItemSelect.prop("disabled", true);
            purchaseItemSelect.val("");
            row.find(".stock-quantity").val("");
        }
    });

    // Add new row
    $("#addRow").click(function () {
        let table = $("#vaccineTable tbody");
        let newRow = table.find("tr:first").clone();
        
        // Clear values but preserve any old values from the form submission
        newRow.find("input").val("");
        newRow.find(".vaccine-select").val("");
        newRow.find(".purchase-item-select").val("").prop("disabled", true);
        newRow.find(".stock-quantity").val("");
        newRow.find("select").prop("selectedIndex", 0);
        
        // Clear error messages in the new row
        newRow.find(".text-danger").remove();
        
        table.append(newRow);
        updateAnimalOptions();
        updateAddButtonVisibility();
    });

    // Remove row
    $(document).on("click", ".remove-row", function () {
        let table = $("#vaccineTable tbody");
        if (table.find("tr").length > 1) {
            $(this).closest("tr").remove();
            updateAnimalOptions();
            updateAddButtonVisibility();
        } else {
            alert("At least one row is required.");
        }
    });

    // Function to update animal options based on selected animals
    function updateAnimalOptions() {
        let selectedAnimals = [];
        $(".animal-select").each(function() {
            let selectedValue = $(this).val();
            if (selectedValue) selectedAnimals.push(selectedValue);
        });
        
        $(".animal-select").each(function() {
            let currentValue = $(this).val();
            $(this).find("option").each(function() {
                if ($(this).val()) {
                    $(this).toggle(
                        !selectedAnimals.includes($(this).val()) || 
                        $(this).val() === currentValue
                    );
                }
            });
        });
    }

    // Function to update add button visibility
    function updateAddButtonVisibility() {
        let totalAnimals = $(".animal-select:first option").length - 1;
        let totalRows = $("#vaccineTable tbody tr").length;
        $("#addRow").toggle(totalRows < totalAnimals);
    }

    // Event handlers for dynamic updates
    $(document).on("change", ".animal-select", function() {
        updateAnimalOptions();
        updateAddButtonVisibility();
    });

    // Initial setup
    updateAnimalOptions();
    updateAddButtonVisibility();
});
</script>
@endsection