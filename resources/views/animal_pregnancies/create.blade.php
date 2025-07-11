@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animals Pregnancies Registration Form</h1>     
        

    <br>

 
    <form  method="POST" enctype="multipart/form-data" action="{{route('animal_pregnancies.store')}}">
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


        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


    <!--this is used to list the female_cow_name -->
        <div class="form-group">

        <label for="female_cow_id">Female Cow Name</label>
        <select name="female_cow_id" id="female_cow_id" class="form-control">
            <option value="">Select Female Cow</option>
           
            @foreach($female_Animals as $female_Animal)
                <option value="{{$female_Animal->id}}"
                {{ old('female_cow_id') == $female_Animal->id ? 'selected' : '' }}
                >{{$female_Animal->animal_name}}</option>
                @endforeach
        </select>
        @error('female_cow_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

    <!--this is used to list the Breeding Event -->
        <div class="form-group">
                <label for="breeding_id">Breeding Event</label>    
            <select name="breeding_id" id="breeding_id" class="form-control">
                    <option value="">Select Breeding Event</option>
                      <!-- This will be filled dynamically using AJAX -->
             </select>
            @error('breeding_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


             <!-- this is used to list the veterinarians-->
        <div class="form-group">
            <label for="veterinarian_id">Veterinarian</label>
           
   
        <select name="veterinarian_id" id="veterinarian_id" class="form-control">
                <option value="">Select the Veterinarian</option>
             
                @foreach($veterinarians as $veterinarian)
                <option value="{{$veterinarian->id}}"
                {{ old('veterinarian_id') == $veterinarian->id ? 'selected':'' }}
                >{{$veterinarian->name}}</option>
                @endforeach
            </select>
            @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        


        
        <!--this is get the estimated_animal_calving date-->
        <div class="form-group">
            <label for="estimated_calving_date">Estimated_calving_date</label>
                <input type="date" name="estimated_calving_date" value="{{ old('estimated_calving_date') }}" class="form-control rounded" id="estimated_calving_date">
                @error('estimated_calving_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <!--this is get the confirmation date-->
        <div class="form-group">
            <label for="confirmation_date">Confirmation_date</label>
                <input type="date" name="confirmation_date" value="{{ old('confirmation_date') }}" class="form-control rounded" id="confirmation_date">
                @error('confirmation_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>




        <!-- this is used to denote the breeding_type-->
        <div class="form-group">

            <label for="pregnancy_status">Pregnancy Status</label><br>

            <input type="radio" id="confirmed" name="pregnancy_status" value="confirmed" {{ old('pregnancy_status') == 'confirmed' ? 'checked' : '' }}>
            <label for="confirmed">Confirmed</label><br>

            <input type="radio" id="pending" name="pregnancy_status" value="pending" {{ old('pregnancy_status') == 'pending' ? 'checked' : '' }} >
            <label for="pending">Pending</label><br>

            <input type="radio" id="failed" name="pregnancy_status" value="failed" {{ old('pregnancy_status') == 'failed' ? 'checked' : '' }}>
            <label for="failed">Failed</label><br>

            
                @error('pregnancy_status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


       

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Register Breedings</button>
    </form>
</div>

@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#female_cow_id').on('change', function () {
            var cowId = $(this).val(); // Get selected female cow ID
            var breedingSelect = $('#breeding_id'); // Breeding event dropdown

            breedingSelect.empty(); // Clear current options
            breedingSelect.append('<option value="">Loading...</option>'); // Show loading

            if (cowId) {
                $.ajax({
                    url: '/animal_pregnancies/get-breeding-events/' + cowId, // Ensure this matches your route
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log("Breeding events:", data); // Log response for debugging

                        breedingSelect.empty(); // Clear options again
                        breedingSelect.append('<option value="">Select Breeding Event</option>');

                        if (data.length > 0) {
                            data.forEach(function (breeding) {
                                breedingSelect.append(
                                    '<option value="' + breeding.id + '">' +
                                    'Breeding ID: ' + breeding.id +
                                    ' | Female Cow: ' + (breeding.femalecow?.animal_name || 'Unknown') +
                                    ' | Male Cow: ' + (breeding.malecow?.animal_name || 'Unknown') +
                                    ' | Date: ' + breeding.breeding_date +
                                    '</option>'
                                );
                            });
                        } else {
                            breedingSelect.append('<option value="">No valid breeding events found</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX error:", status, error);
                        breedingSelect.empty().append('<option value="">Error loading breeding events</option>');
                    }
                });
            } else {
                breedingSelect.empty().append('<option value="">Select Breeding Event</option>');
            }
        });
    });
</script>

@endsection
