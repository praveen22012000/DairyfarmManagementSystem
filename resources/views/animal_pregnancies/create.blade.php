@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Animals Pregnancies Registration Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('animal_pregnancies.store')}}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>


    <!--this is used to list the female_cow_name -->
        <div class="form-group">

        <label for="female_cow_id">Female Cow Name</label>
        <select name="female_cow_id" id="female_cow_id" class="form-control">
            <option value="">Select Female Cow</option>
           
            @foreach($female_Animals as $female_Animal)
                <option value="{{$female_Animal->id}}">{{$female_Animal->animal_name}}</option>
                @endforeach
        </select>
        @error('female_cow_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

    <!--this is used to list the Breeding Event -->
        <div class="form-group">
            <label for="breeding_id">Breeding Event</label>
           
        
        <select name="breeding_id" id="breeding_id" class="form-control">
                <option value="">Select Breeding Event</option>
             
                @foreach($breedings as $breeding)
                <option value="{{$breeding->id}}">
                Breeding ID: {{ $breeding->id }} | Female Cow: {{ $breeding->femalecow->animal_name ?? 'Unknown' }} | Male Cow: {{ $breeding->malecow->animal_name ?? 'Unknown' }} | Date: {{ $breeding->breeding_date }}
                </option>
                @endforeach
            </select>
            @error('male_cow_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


             <!-- this is used to list the veterinarians-->
        <div class="form-group">
            <label for="veterinarian_id">Veterinarian</label>
           
   
        <select name="veterinarian_id" id="veterinarian_id" class="form-control">
                <option value="">Select the Veterinarian</option>
             
                @foreach($veterinarians as $veterinarian)
                <option value="{{$veterinarian->id}}">{{$veterinarian->name}}</option>
                @endforeach
            </select>
            @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        


        
        <!--this is get the estimated_animal_calving date-->
        <div class="form-group">
            <label for="estimated_calving_date">Estimated_calving_date</label>
                <input type="date" name="estimated_calving_date" class="form-control rounded" id="estimated_calving_date">
                @error('estimated_calving_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <!--this is get the confirmation date-->
        <div class="form-group">
            <label for="confirmation_date">Confirmation_date</label>
                <input type="date" name="confirmation_date" class="form-control rounded" id="confirmation_date">
                @error('confirmation_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>




        <!-- this is used to denote the breeding_type-->
        <div class="form-group">

            <label for="pregnancy_status">Pregnancy Status</label><br>

            <input type="radio" id="confirmed" name="pregnancy_status" value="confirmed">
            <label for="confirmed">Confirmed</label><br>

            <input type="radio" id="pending" name="pregnancy_status" value="pending">
            <label for="pending">Pending</label><br>

            <input type="radio" id="failed" name="pregnancy_status" value="failed">
            <label for="failed">Failed</label><br>

            
                @error('pregnancy_status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


       

        </fieldset>

        
        <button type="submit" class="btn btn-success mt-3">Register Breedings</button>
    </form>
</div>

@endsection
