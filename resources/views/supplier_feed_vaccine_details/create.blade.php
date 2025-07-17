@extends('layouts.admin.master')

@section('content')
       

       
<div class="col-md-12">

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{route('supply_feed_vaccine.store')}}">

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


    <h1 style="text-align:center;">Supplier Registration Form</h1>     
        
    <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="name">Supplier Name</label>
            <input type="text" id="name" name="name" class="form-control rounded" value="{{ old('name') }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="phone_no">Phone Number</label>
            <input type="text" id="phone_no" name="phone_no" class="form-control rounded" value="{{ old('phone_no') }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('phone_no') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="email">Email</label>
            <input type="text" id="email" name="email" class="form-control rounded" value="{{ old('email') }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control rounded" value="{{ old('address') }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('address') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
<!--
        <div class="mb-4">
                <label for="feeds" class="block text-gray-700 font-medium" >Feeds:</label>
                    <select name="feeds[]" id="feeds"  class="form-control" multiple>
                        @foreach($feeds as $feed)
                            <option value="{{ $feed->id }}">{{ $feed->feed_name }}</option>
                        @endforeach
                    </select>
                    @error('feeds[]') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
                <label for="vaccines" >Vaccines:</label>
                    <select name="vaccines[]" id="vaccines" class="form-control" multiple>
                        @foreach($vaccines as $vaccine)
                            <option value="{{ $vaccine->id }}">{{ $vaccine->vaccine_name }}</option>
                        @endforeach
                    </select>
                    @error('vaccines') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
-->
        
        
        <button type="submit" class="btn btn-success mt-3">Save Record</button>
    </form>

</div>

@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js">

</script>




<script>




</script>






@endsection



      




