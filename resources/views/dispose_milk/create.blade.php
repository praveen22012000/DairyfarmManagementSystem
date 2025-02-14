@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Dispose Milk Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


        <div class="form-group">
        <label for="production_milk_id">Milk Production Item</label>
        <select name="production_milk_id" id="production_milk_id" class="form-control" >
            <option value="">Select the Milk Production Item</option>
            @foreach(milk_production_details as milk_production_detail)
                <option value=""></option>
            @endforeach
        </select>
        @error('calf_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    
        <div class="form-group">
            <label for="veterinarian_id">Veterinarian</label>
           
        <!-- this is used to list the veterinarian_id-->
        <select name="veterinarian_id" id="veterinarian_id" class="form-control" required>
                <option value="">Select Veterinarian</option>
             
               @foreach($veterinarians as $veterinarian)
                <option value="{{$veterinarian->id}}">{{$veterinarian->name}}</option>
                @endforeach
            </select>

            @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div class="form-group">
            <label for="pregnancy_id">Pregnancy ID</label>
           
        <!-- this is used to list the pregnancy_id-->
        <select name="pregnancy_id" id="pregnancy_id" class="form-control" required>
                <option value="">Select Pregnancy</option>
             
               @foreach($pregnancies as $preg)
                    <option value="{{$preg->id}}">
                         Breeding_ID: {{ $preg->breeding_event->id ?? 'N/A' }} |
                            Female_cow: {{ $preg->AnimalDetail->animal_name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>

            @error('pregnancy_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>


        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="calving_date">Calving_Date</label>
                <input type="date" name="calving_date" class="form-control rounded" id="calving_date" readonly required>
        </div>

        <!--this is used to record the calving notes -->
        <div class="form-group">
            <label for="calving_notes">Calving_Notes</label>
            <textarea class="form-control" id="calving_notes" name="calving_notes" rows="4" placeholder="Enter the calving notes here"></textarea>
            @error('calving_notes') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Register Calvings</button>
    </form>

</div>
@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>

$(document).ready(function () {
    $('#calf_id').on('change', function () {
        var calfId = $(this).val(); // Get the selected calf ID

        if (calfId) {
            // Send an AJAX request to fetch calving details
            $.ajax({
                url: `/animal_calvings/${calfId}/details`,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // If data is returned, populate the form fields
                    if (data) {
                        $('#calving_date').val(data.calving_date);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching calving details:', error);
                }
            });
        }
    });
});




</script>

@endsection