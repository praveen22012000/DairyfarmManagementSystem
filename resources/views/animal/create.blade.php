@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animal Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal.store')}}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>

   


         <!-- this is used to list the animal types-->
        <div class="form-group">
            <label for="animal_type_id">Animal Type</label>
           
        <!-- this is used to list the animal types-->
        <select name="animal_type_id" id="animal_type_id" class="form-control" >
                <option value="">Select Animal Type</option>
             
                @foreach ($animal_types as $animal_type)
                <option value="{{ $animal_type->id }}">{{ $animal_type->animal_type}}</option>
                @endforeach
            </select>
            @error('animal_type_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
        <!--this is get the animal birthdate-->
        <div class="form-group">
            <label for="animal_birthdate">Animal Birthdate</label>
                <input type="date" name="animal_birthdate" class="form-control rounded" id="animal_birthdate">
                @error('animal_birthdate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!--this is to get the Animalname-->
        <div class="form-group">
            <label for="animal_name">Animal Name</label>
            <input type="text" name="animal_name" class="form-control rounded" id="animal_name" >
            @error('animal_name') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <option value="{{ $male_animal->id }}">{{ $male_animal->animal_name }}</option>
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
                        <option value="{{ $female_Animal->id }}">{{ $female_Animal->animal_name }}</option>
                            @endforeach
                        
                    </select>
                    @error('dam_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



        </div>


         
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ear_tag" class="form-label">Ear Tag</label>
                    <input type="text" class="form-control rounded" id="ear_tag" name="ear_tag" placeholder="Enter Ear Tag">
                    @error('ear_tag') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

       
                <div class="col-md-6">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control rounded" id="color" name="color" placeholder="Enter Color">
                    @error('color') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
        </div>

        

            <!--this is to list the breed types -->
       

        
        </fieldset>
       

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Other Information</legend>

        <div class="row mb-3">
            
                <div class="col-md-6">
                        <label for="weight_at_birth">Weight at Birth (Kg)</label>
                        <input type="text" name="weight_at_birth" class="form-control rounded" id="weight_at_birth" >
                        @error('weight_at_birth') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                        <label for="age_at_first_service">Age at First Service</label>
                        <input type="text" name="age_at_first_service" class="form-control rounded" id="age_at_first_service" >
                        @error('age_at_first_service') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
        </div>


       
        <div class="form-group">
            <label for="weight_at_first_service">Weight At First Service (Kg)</label>
            <input type="text" name="weight_at_first_service" class="form-control rounded" id="weight_at_first_service">
            @error('weight_at_first_service') <span class="text-danger">{{ $message }}</span> @enderror
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
@endsection
