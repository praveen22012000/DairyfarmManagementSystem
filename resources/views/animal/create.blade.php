@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center">Animal Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal.store')}}">
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

   


         <!-- this is used to list the animal types-->
        <div class="form-group">
            <label for="animal_type_id">Animal Type</label>
           
        <!-- this is used to list the animal types-->
        <select name="animal_type_id" id="animal_type_id" class="form-control" >
                <option value="">Select Animal Type</option>
             
                @foreach ($animal_types as $animal_type)
                <option value="{{ $animal_type->id }}"
                {{ old('animal_type_id') == $animal_type->id ? 'selected' : '' }}
                >{{ $animal_type->animal_type}}</option>
                @endforeach
            </select>
            @error('animal_type_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
        <!--this is get the animal birthdate-->
        <div class="form-group">
            <label for="animal_birthdate">Animal Birthdate</label>
                <input type="date" name="animal_birthdate" class="form-control rounded" value="{{ old('animal_birthdate') }}" id="animal_birthdate">
                @error('animal_birthdate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!--this is to get the Animalname-->
        <div class="form-group">
            <label for="animal_name">Animal Name</label>
            <input type="text" name="animal_name" class="form-control rounded" value="{{ old('animal_name') }}" id="animal_name" >
            @error('animal_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
                <select name="status" id="status" class="form-control"  style="width: 100%;">
                    <option value="alive" {{ old('status') == 'alive' ? 'selected' : '' }} >Alive</option>
                    <option value="deceased" {{ old('status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                </select>
        </div>


        <div class="form-group d-none" id="death_date_container">
            <label for="death_date" class="block font-semibold">Death Date</label>
            <input type="date" name="death_date" id="death_date" value="{{ old('death_date') }}" class="form-control rounded">
        </div>




        </fieldset>

      

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Descriptive Information</legend>


        <!-- Checkbox for Known Parents -->
        <div>
            <label>
                <input type="checkbox" id="parentKnownCheckbox">
                Known Parents
            </label>
        </div>


        <div class="row mb-3">
        <!-- Sire ID Dropdown -->
            <div class="col-md-6">
                    <label for="sire_id">Sire (Male Cow):</label><br>
                    <select name="sire_id" id="sire_id"  class="form-control">
                    <option value="">Select Sire</option>
                            @foreach($male_animals as $male_animal)
                        <option value="{{ $male_animal->id }}"
                        {{old('sire_id') == $male_animal->id ? 'selected' : '' }}
                        >{{ $male_animal->animal_name }}</option>
                            @endforeach
                    </select>
                    @error('sire_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        <!-- Dam ID Dropdown -->
            <div class="col-md-6">
                    <label for="dam_id">Dam (Female Cow):</label><br>
                    <select name="dam_id" id="dam_id"  class="form-control">

                    <option value="">Select Dam</option>
                            @foreach($female_Animals as $female_Animal)
                        <option value="{{ $female_Animal->id }}"
                      {{  old('dam_id') == $female_Animal->id ? 'selected' : '' }}
                        >{{ $female_Animal->animal_name }}</option>
                            @endforeach
                        
                    </select>
                    @error('dam_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


        </div>


         
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ear_tag" class="form-label">Ear Tag</label>
                    <input type="text" class="form-control rounded" id="ear_tag" value="{{ old('ear_tag') }}" name="ear_tag" placeholder="Enter Ear Tag">
                    @error('ear_tag') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

       
                <div class="col-md-6">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control rounded" id="color" value="{{ old('color') }}" name="color" placeholder="Enter Color">
                    @error('color') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
        </div>

        

            <!--this is to list the breed types -->
       

        
        </fieldset>
       

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Other Information</legend>

        <div class="row mb-3">
            
                <div class="col-md-4">
                        <label for="weight_at_birth">Weight at Birth (Kg)</label>
                        <input type="text" name="weight_at_birth" value="{{ old('weight_at_birth') }}" class="form-control rounded" id="weight_at_birth" >
                        @error('weight_at_birth') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                        <label for="age_at_first_service">Age at First Service</label>
                        <input type="text" name="age_at_first_service" value="{{ old('age_at_first_service') }}" class="form-control rounded" id="age_at_first_service" >
                        @error('age_at_first_service') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                        <label for="weight_at_first_service">Weight At First Service (Kg)</label>
                        <input type="text" name="weight_at_first_service" class="form-control rounded" value="{{ old('weight_at_first_service') }}" id="weight_at_first_service">
                        @error('weight_at_first_service') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
        </div>


       
      

        
        <button type="submit" class="btn btn-success mt-3">Register Animal</button>


        
    </form>
</div>

@endsection

        <!-- below is added by me-->
@section('js')
<script>
   
   document.getElementById('parentKnownCheckbox').addEventListener('change', function() {
        let sireDropdown = document.getElementById('sire_id');
        let damDropdown = document.getElementById('dam_id');
        let isChecked = this.checked;
        
        // Enable or disable dropdowns based on checkbox state
        sireDropdown.disabled = !isChecked;
        damDropdown.disabled = !isChecked;
            });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {// This waits until the entire HTML page has fully loaded before running the code

        let deathDateContainer = document.getElementById('death_date_container');// Finds the element with the id="death_date_container"
        let statusSelect = document.getElementById('status');// Finds the element with the id="status"

        // Hide the death date field when the page loads
        if (statusSelect.value === 'alive') // This checks the initial value of the status dropdown.
        {
            deathDateContainer.classList.add('d-none');//If it's set to "alive", it hides the death date field by adding the Bootstrap class d-none:
        }

        // Listen for status change
        statusSelect.addEventListener('change', function () 
        {
            if (this.value === 'deceased') {
                deathDateContainer.classList.remove('d-none'); // Show when deceased is selected
            } else {
                deathDateContainer.classList.add('d-none'); // Hide when alive is selected
            }
        });
    });
</script>



@endsection
