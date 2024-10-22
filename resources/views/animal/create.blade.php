@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animal Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal.store')}}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>

    <!--this is to display the image of the animal-->
        <div class="form-group">
            <label for="animal_image">Animal Image</label>
            <input type="file" name="animal_image" class="form-control" id="animal_image" required>
        </div>



         <!-- this is used to list the animal types-->
        <div class="form-group">
            <label for="animal_type_id">Animal Type</label>
           
        <!-- this is used to list the animal types-->
        <select name="animal_type_id" id="animal_type_id" class="form-control" required>
                <option value="">Select Animal Type</option>
             
                @foreach ($animal_types as $animal_type)
                <option value="{{ $animal_type->id }}">{{ $animal_type->animal_type}}</option>
                @endforeach
            </select>

        </div>

        
        <!--this is get the animal birthdate-->
        <div class="form-group">
            <label for="animal_birthdate">Animal Birthdate</label>
                <input type="datetime-local" name="animal_birthdate" class="form-control rounded" id="animal_birthdate" required>
        </div>

        <!--this is to get the Animalname-->
        <div class="form-group">
            <label for="animal_name">Animal Name</label>
            <input type="text" name="animal_name" class="form-control rounded" id="animal_name" required>
        </div>

        </fieldset>

      

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Descriptive Information</legend>
       
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ear_tag" class="form-label">Ear Tag</label>
                    <input type="text" class="form-control rounded" id="ear_tag" name="ear_tag" placeholder="Enter Ear Tag">
                </div>
                <div class="col-md-6">
                    <label for="sire_id" class="form-label">Sire ID</label>
                    <input type="text" class="form-control rounded" id="sire_id" name="sire_id" placeholder="Enter Sire ID">
                </div>
        </div>


        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="dam_id" class="form-label">Dam ID</label>
                    <input type="text" class="form-control rounded" id="dam_id" name="dam_id" placeholder="Enter Dam ID">
                </div>
                <div class="col-md-6">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control rounded" id="color" name="color" placeholder="Enter Color">
                </div>
        </div>

        

            <!--this is to list the breed types -->
        <div class="form-group">
            <label for="breed_id">Breed</label>
           
        <!-- this is used to list the breed types-->

        <select name="breed_id" id="breed_id" class="form-control" required>
                <option value="">Select Breed Type</option>
             
                @foreach ($breeds as $breed)
                <option value="{{ $breed->id }}">{{ $breed->breed_name }}</option>
                @endforeach
            </select>
            

        </div>

        
        </fieldset>
       

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Other Information</legend>

        <div class="row mb-3">
            
                <div class="col-md-6">
                        <label for="weight_at_birth">Weight_at_Birth</label>
                        <input type="text" name="weight_at_birth" class="form-control rounded" id="weight_at_birth" required>
                </div>

                <div class="col-md-6">
                        <label for="age_at_birth">Age_at_First_Service</label>
                        <input type="text" name="age_at_birth" class="form-control rounded" id="age_at_birth" required>
                </div>
        </div>


       
        <div class="form-group">
            <label for="weight_at_first_service">Weight_at_First_Service</label>
            <input type="text" name="weight_at_first_service" class="form-control rounded" id="weight_at_first_service" required>
        </div>

        
        <button type="submit" class="btn btn-success mt-3">Register Animal</button>
    </form>
</div>

@endsection
