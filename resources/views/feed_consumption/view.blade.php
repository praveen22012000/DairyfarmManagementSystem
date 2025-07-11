@extends('layouts.admin.master')

@section('content')
<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Feed Consumption Details</h1>

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

        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="form-group">
                <label for="animal_id">Animal Name</label>
                    <select name="animal_id" id="animal_id" class="form-control" >
                        <option value="">Select Animal</option>
                            @foreach($animals as $animal)
                        <option value="{{ $animal->id }}"
                        {{$feedconsumeitem->feed_consume_details->animal_id == $animal->id ? 'selected':'' }}>
                        {{ $animal->animal_name }}</option>
                            @endforeach
                    </select>
                @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" id="date" class="form-control rounded" value="{{ $feedconsumeitem->feed_consume_details->date }}" >
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <br>
            <div>
                <label for="time" class="block text-lg font-medium text-gray-700 mb-2">Time</label>
                <input type="time" name="time" id="time" class="form-control rounded" value="{{ $feedconsumeitem->feed_consume_details->time }}" >
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
                      
                    </tr>
                </thead>
                <tbody>
                 

                    <tr  class="milk-row">

                    
                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <select name="feed_id" id="feed_id" class="feed-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">Select the Feed</option>
                                    @foreach($feeds as $feed)
                                    <option value="{{$feed->id}}"
                                         {{$feedconsumeitem->feed_id == $feed->id ? "selected" : " "  }}
                                            >
                                                    {{$feed->feed_name}}
                                    </option>
                                    @endforeach 
                            </select>
                                @error('feed_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="number" class="form-control" name="quantity" value="" readonly>
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                            <input type="text" name="consumed_quantity" value="{{ $feedconsumeitem->consumed_quantity }}" class="form-control"  style="width: 80px;">
                            @error('consumed_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                        <input type="text" name="notes" class="form-control"  style="width:200px;" value="{{ $feedconsumeitem->notes }}">
                        @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                        <td class="border-t px-6 py-4 text-left text-gray-800">
                                <select name="purchase_feed_item_id" id="purchase_feed_item_id" class="purchase-item-select border border-gray-400 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                        <option value="">Select the Purchase Feed Item</option>
                                            @foreach($purchaseFeedItems as $item)
                                                    <option value="{{ $item->id }}" 
                                                    {{ $feedconsumeitem->purchase_feed_item_id == $item->id ? 'selected' : ''   }}
                                                        data-feed-id="{{ $item->feed_id }}"
                                                        data-stock-quantity="{{ $item->stock_quantity }}"
                                                         >
                                                                    {{ $item->id.'|'.$item->feed->feed_name.'|'.$item->manufacture_date }}
                                                    </option>
                                            @endforeach
                                </select>
                                    @error('purchase_feed_item_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </td>

                      
                    </tr>
                  
                </tbody>
            </table>
        </div>

        
    </form>
</div>


@endsection

@section('js')
<script>
    $(document).ready(function () {
        let today = new Date().toISOString().split("T")[0];
        $("#date").attr("max", today);

        //  Function to update stock quantity (FIXED to allow 0)
        function updateStockQuantity(selectElement) // This defines a function called updateStockQuantity.
        {
            let selectedOption = $(selectElement).find(":selected");//.find(":selected") gets the <option> that is currently selected in the dropdown.
            let stockQuantity = selectedOption.data("stock-quantity");// .data("stock-quantity") gets the value of the attribute data-stock-quantity from the selected <option>

            //  Allow 0, only replace if null or undefined
            $(selectElement).closest("tr").find("input[name='quantity']").val
            (
                stockQuantity !== undefined && stockQuantity !== null ? stockQuantity : '' // This checks two things:Is stockQuantity not undefined? Is stockQuantity not null? This condition is true if stockQuantity has a real value, even if it's 0.
            );
        }

        // Function to filter purchase items based on selected feed
        function filterPurchaseItems(feedSelect) //This declares a function named filterPurchaseItems.It takes one parameter: feedSelect, which is the <select> dropdown for feed names 
        {
            let row = $(feedSelect).closest("tr");//
            let purchaseItemSelect = row.find(".purchase-item-select");//Inside the same row, it finds the <select> dropdown with class .purchase-item-select
            let selectedFeedId = $(feedSelect).val();//Gets the currently selected feed ID from the feedSelect dropdown.

            if (selectedFeedId) //Checks if the user has selected a feed (i.e., not empty).
            {
                purchaseItemSelect.prop("disabled", false);// Enables the purchase item dropdown by removing the disabled attribute.

                purchaseItemSelect.find("option").each(function () { //Loops through each <option> tag in the purchase item dropdown.
                    let optionFeedId = $(this).data("feed-id");//Gets the data-feed-id value of the current <option>.

                    if (optionFeedId == selectedFeedId || $(this).val() === "") //If the option's feed-id matches the selected feed
                    {
                        $(this).show();//Makes the matching option visible in the dropdown.
                    } 
                    else //If the feed ID doesn't match...
                    {
                        $(this).hide();//Hide the option from the dropdown.


                        if ($(this).prop("selected") && optionFeedId != selectedFeedId) 
                        {
                            purchaseItemSelect.val("");
                            row.find("input[name='quantity']").val("");
                        }
                    }
                });
            } 
            else 
            {
                purchaseItemSelect.prop("disabled", true);
                purchaseItemSelect.val("");
                row.find("input[name='quantity']").val("");
            }
        }

        //  Initialize existing rows (on page load)
        $(".feed-select").each(function () {
            filterPurchaseItems(this);
            let purchaseSelect = $(this).closest("tr").find(".purchase-item-select");
            if (purchaseSelect.val()) {
                updateStockQuantity(purchaseSelect);
            }
        });

        //  On change handlers
        $(document).on("change", ".purchase-item-select", function () {
            updateStockQuantity(this);
        });

        $(document).on("change", ".feed-select", function () {
            filterPurchaseItems(this);
        });
    });
</script>
@endsection
