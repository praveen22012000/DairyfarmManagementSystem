@extends('layouts.admin.master')
@section('content')
           <!-- Animal Registration Form (Right Half) -->
<div class="col-md-12">

       
            <h1 style="text-align:center">Animal Registration Form</h1>     
            
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="#">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>

    



         <!-- this is used to list the animal types-->
        <div class="form-group">
            <label for="animal_type_id">Animal Type</label>
           
        <!-- this is used to list the animal types-->
        <select name="animal_type_id" id="animal_type_id" class="form-control" required>
                <option value="">Select Animal Type</option>
             
                @foreach ($animal_types as $animal_type)
                <option value="{{ $animal_type->id }}"
                {{ $animaldetail->animal_type_id == $animal_type->id ? 'selected' : '' }}
                >{{ $animal_type->animal_type}}</option>
                @endforeach
            </select>

        </div>

        
        <!--this is get the animal birthdate-->
        <div class="form-group">
            <label for="animal_birthdate">Animal Birthdate</label>
                <input type="date" name="animal_birthdate" class="form-control rounded" id="animal_birthdate" value="{{  $animaldetail->animal_birthdate}}" required>
        </div>

       


        <!--this is to get the Animalname-->
        <div class="form-group">
            <label for="animal_name">Animal Name</label>
            <input type="text" name="animal_name" class="form-control rounded" id="animal_name" required value="{{$animaldetail->animal_name}}">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
                <select name="status" id="status" class="form-control" style="width: 100%;" disabled>
                    <option value="alive"  {{ $animaldetail->status == 'alive' ? 'selected' : '' }}>Alive</option>
                    <option value="deceased" {{ $animaldetail->status == 'deceased' ? 'selected' : '' }}>Deceased</option>
                </select>
        </div>


        <div class="form-group d-none" id="death_date_container">
            <label for="death_date" class="block font-semibold">Death Date</label>
            <input type="date" name="death_date" id="death_date" class="form-control rounded" value="{{ $animaldetail->death_date }}">
            @error('death_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        </fieldset>

      

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Descriptive Information</legend>


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

                    <option value="null" {{ is_null($animaldetail->sire_id) ? 'selected' : '' }}>No Sire (null)</option> 

                            @foreach($male_animals as $male_animal)
                        <option value="{{ $male_animal->id }}"
                        {{ $animaldetail->sire_id == $male_animal->id ? 'selected' : '' }}

                        >{{ $male_animal->animal_name }}</option>
                            @endforeach
                    </select>
            </div>

            <!-- Dam ID Dropdown -->
            <div class="col-md-6">
                    <label for="dam_id">Dam (Female Cow):</label><br>
                    <select name="dam_id" id="dam_id"  class="form-control" >

                    <option value="">Select Dam</option>

                    <option value="null" {{ is_null($animaldetail->dam_id) ? 'selected' : '' }}>No Dam (null)</option> 

                            @foreach($female_Animals as $female_Animal)
                        <option value="{{ $female_Animal->id }}"
                        {{$animaldetail->dam_id == $female_Animal->id ? 'selected': ''}}
                        >{{ $female_Animal->animal_name }}</option>
                            @endforeach
                        
                    </select>
            </div>

              
        </div>


        <div class="row mb-3">


                <div class="col-md-6">
                    <label for="ear_tag" class="form-label">Ear Tag</label>
                    <input type="text" class="form-control rounded" id="ear_tag" name="ear_tag" placeholder="Enter Ear Tag" value="{{$animaldetail->ear_tag}}">
                </div>
               
                <div class="col-md-6">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control rounded" id="color" name="color" placeholder="Enter Color" value="{{$animaldetail->color}}">
                </div>
        </div>

        


        
        </fieldset>
       

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Other Information</legend>

        <div class="row mb-3">
            
                <div class="col-md-6">
                        <label for="weight_at_birth">Weight_at_Birth</label>
                        <input type="text" name="weight_at_birth" class="form-control rounded" id="weight_at_birth" value="{{$animaldetail->weight_at_birth}}"  required>
                </div>

                <div class="col-md-6">
                        <label for="age_at_first_service">Age_at_First_Service</label>
                        <input type="text" name="age_at_first_service" class="form-control rounded" id="age_at_first_service" value="{{$animaldetail->age_at_first_service}}" required>
                </div>
        </div>


       
        <div class="form-group">
            <label for="weight_at_first_service">Weight_at_First_Service</label>
            <input type="text" name="weight_at_first_service" class="form-control rounded" id="weight_at_first_service" value="{{$animaldetail->weight_at_first_service}}" required>
        </div>

        <a href="{{ route('animal.list') }}" class="btn btn-info">Back</a>

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
    document.addEventListener("DOMContentLoaded", function() {
        let deathDateContainer = document.getElementById('death_date_container');
        let statusSelect = document.getElementById('status');

        // Hide the death date field when the page loads
        if (statusSelect.value === 'alive') {
            deathDateContainer.classList.add('d-none');
        }

        if (statusSelect.value === 'deceased') {
            deathDateContainer.classList.remove('d-none');
        }

        // Listen for status change
        statusSelect.addEventListener('change', function () {
            if (this.value === 'deceased') {
                deathDateContainer.classList.remove('d-none'); // Show when deceased is selected
            } else {
                deathDateContainer.classList.add('d-none'); // Hide when alive is selected
            }
        });
    });
</script>



@endsection
