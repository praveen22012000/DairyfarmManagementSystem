@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('feed_vaccine.update',$feed->id)}}">

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


    <h1 style="text-align:center;">Feed  Registration Form</h1>     
        
        <!-- Name Field -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="feed_name">Feed or Vaccine Name</label>
            <input type="text" id="feed_name" name="feed_name" class="form-control rounded" value="{{ old('feed_name',$feed->feed_name) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
        </div>

        <!-- Manufacturer Field -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="manufacturer">Manufacturer</label>
            <input type="text" id="manufacturer" class="form-control rounded" name="manufacturer" value="{{ old('manufacturer',$feed->manufacturer) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
        </div>


      

        <!-- Unit Type ENUM Dropdown -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="unit_type">Unit Type</label>
            <select id="unit_type" name="unit_type" class="form-control" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                <option value="">Select the Unit Type</option>
                <option value="g" {{ old('unit_type', $feed->unit_type) == 'g' ? 'selected' : '' }}>Grams (g)</option>
                 <option value="mg" {{ old('unit_type', $feed->unit_type) == 'mg' ? 'selected' : '' }}>Milligrams (mg)</option>
                <option value="kg" {{ old('unit_type', $feed->unit_type) == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                <option value="ml" {{ old('unit_type', $feed->unit_type) == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="unit_price">Unit Price (Rs.)</label>
            <input type="text" id="unit_price" name="unit_price" class="form-control rounded" value="{{ old('unit_price',$feed->unit_price) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
        </div>

        
        <button type="submit" class="btn btn-success mt-3">Update</button>
      
    </form>

</div>

@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js">

</script>




<script>




</script>






@endsection