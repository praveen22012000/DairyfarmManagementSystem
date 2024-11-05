@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animal Calvings Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal_calvings.store')}}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


        <div class="form-group">
        <label for="calf_id">Female Cow Name</label>
        <select name="calf_id" id="calf_id" class="form-control">
            <option value="">Select Female Cow</option>
           
            @foreach($female_Animals as $female_Animal)
                <option value="{{$female_Animal->id}}">{{$female_Animal->animal_name}}</option>
                @endforeach
        </select>
    </div>


        <div class="form-group">
            <label for="parent_cow_id">Male Cow Name</label>
           
        <!-- this is used to list the parent_cow_id-->
        <select name="parent_cow_id" id="parent_cow_id" class="form-control" required>
                <option value="">Select male Animal</option>
             
                @foreach($male_animals as $male_animal)
                <option value="{{$male_animal->id}}">{{$male_animal->animal_name}}</option>
                @endforeach
            </select>

        </div>


        <div class="form-group">
            <label for="parent_cow_id">Veterinarian</label>
           
        <!-- this is used to list the parent_cow_id-->
        <select name="parent_cow_id" id="parent_cow_id" class="form-control" required>
                <option value="">Select the Veterinarian</option>
             
                @foreach($veterinarians as $veterinarian)
                <option value="{{$veterinarian->id}}">{{$veterinarian->name}}</option>
                @endforeach
            </select>

        </div>


        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="calving_date">Calving_Date</label>
                <input type="date" name="calving_date" class="form-control rounded" id="calving_date" required>
        </div>

        <!--this is used to record the calving notes -->
        <div class="form-group">
            <label for="calving_notes">Calving_Notes</label>
            <textarea class="form-control" id="calving_notes" name="calving_notes" rows="4" placeholder="Enter the calving notes here"></textarea>
        </div>

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Register Calvings</button>
    </form>
</div>

@endsection
