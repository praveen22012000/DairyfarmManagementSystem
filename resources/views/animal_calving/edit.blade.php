@extends('layouts.admin.master')

@section('content')
    <div class="col-md-12">
        <h1>Animal Calvings Registration Form</h1>


        <br>

        <form method="POST" enctype="multipart/form-data" action="{{ route('animal_calvings.update', $animalcalvings->id) }}">
            @csrf

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto px-2">General Information</legend>

                <div class="form-group">
                    <label for="calf_id">Calf Name</label>
                    <select name="calf_id" id="calf_id" class="form-control">
                        <option value="">Select Calf</option>
                        @foreach($Calf_animals as $Calf_animal)
                            <option value="{{ $Calf_animal->calf_id }}"
                                {{ $animalcalvings->calf_id == $Calf_animal->calf_id ? 'selected' : '' }}>
                                {{ $Calf_animal->calf->animal_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('calf_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="veterinarian_id">Veterinarian</label>
                    <select name="veterinarian_id" id="veterinarian_id" class="form-control" required>
                        <option value="">Select Veterinarian</option>
                        @foreach($veterinarians as $veterinarian)
                            <option value="{{ $veterinarian->id }}"
                                {{ $animalcalvings->veterinarian_id == $veterinarian->id ? 'selected' : '' }}>
                                {{ $veterinarian->name }}
                            </option>
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
                            <option value="{{$preg->id}}"
                            {{$animalcalvings->pregnancy_id == $preg->id ? 'selected' : '' }}>

                                Breeding_ID: {{ $preg->breeding_event->id ?? 'N/A' }} |
                                Female_cow: {{ $preg->AnimalDetail->animal_name ?? 'N/A' }}
                            </option>
                        @endforeach

                    </select>

                    @error('pregnancy_id') <span class="text-danger">{{ $message }}</span> @enderror

                </div>


                <div class="form-group">
                    <label for="calving_date">Calving Date</label>
                    <input type="date" name="calving_date" class="form-control rounded" id="calving_date" value="{{ $animalcalvings->calving_date }}" required readonly>
                   
                </div>

                <div class="form-group">
                    <label for="calving_notes">Calving Notes</label>
                    <textarea class="form-control" id="calving_notes" name="calving_notes" rows="4" placeholder="Enter the calving notes here">{{ $animalcalvings->calving_notes }}</textarea>
                    @error('calving_notes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </fieldset>

            <button type="submit" class="btn btn-success mt-3">Update Calvings</button>
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
