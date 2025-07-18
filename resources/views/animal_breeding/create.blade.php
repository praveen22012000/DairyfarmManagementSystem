@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animal Breedings Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal_breedings.store')}}">
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

       


    <!--this is used to list the female_cow_name -->
        <div class="form-group">

        <label for="female_cow_id">Female Cow Name</label>
        <select name="female_cow_id" id="female_cow_id" class="form-control">
            <option value="">Select Female Cow</option>
           
            @foreach($female_Animals as $female_Animal)
                <option value="{{$female_Animal->id}}"  
              {{  old('female_cow_id') == $female_Animal->id ? 'selected' : '' }}
                >{{$female_Animal->animal_name}}</option>
                @endforeach
        </select>
        @error('female_cow_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>


        <div class="form-group">
            <label for="male_cow_id">Male Cow Name</label>
           
        
        <select name="male_cow_id" id="male_cow_id" class="form-control">
                <option value="">Select male Animal</option>
             
                @foreach($male_animals as $male_animal)
                <option value="{{$male_animal->id}}"
               {{ old('male_cow_id') == $male_animal->id ? 'selected' : ''}}
                >{{$male_animal->animal_name}}</option>
                @endforeach
            </select>
            @error('male_cow_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <div class="form-group">
            <label for="veterinarian_id">Veterinarian</label>
           
        <!-- this is used to list the parent_cow_id-->
        <select name="veterinarian_id" id="veterinarian_id" class="form-control">
                <option value="">Select the Veterinarian</option>
             
                @foreach($veterinarians as $veterinarian)
                <option value="{{$veterinarian->id}}"
                {{ old('veterinarian_id') == $veterinarian->id ? 'selected' : '' }}
                >{{$veterinarian->name}}</option>
                @endforeach
            </select>
            @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="breeding_date">Breeding Date</label>
                <input type="date" name="breeding_date" class="form-control rounded" id="breeding_date" value="{{ old('breeding_date') }}">
                @error('breeding_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <!-- this is used to denote the breeding_type-->
        <div class="form-group">

            <label for="insemination_type">Breeding Type</label><br>


            <input type="radio" id="Artificial Insemination" name="insemination_type" value="Artificial Insemination" {{ old('insemination_type') == 'Artificial Insemination' ? 'checked' : '' }}>
            <label for="Artificial Insemination">Artificial Insemination</label><br>

            <input type="radio" id="Natural Mating" name="insemination_type" value="Natural Mating" {{ old('insemination_type') == 'Natural Mating' ? 'checked' : '' }}>
            <label for="Natural Mating">Natural Mating</label><br>

            
                @error('insemination_type') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <!--this is used to record the calving notes -->
        <div class="form-group">
            <label for="notes">Breeding_Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter the breeding notes here">{{ old('notes') }}</textarea>
            @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


       

        
        <button type="submit" class="btn btn-success mt-3">Register Breedings</button>
    </form>
</div>

@endsection
