@extends('layouts.admin.master')

@section('content')
       
@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

    <br>

    <form  method="POST" enctype="multipart/form-data" action="">

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


    <h1>Supplier Registration Form </h1>     
        
    <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="name">Supplier Name</label>
            <input type="text" id="name" name="name" class="form-control rounded" value="{{ old('name', $supplier->name) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="phone_no">Phone Number</label>
            <input type="text" id="phone_no" name="phone_no" class="form-control rounded" value="{{ old('phone_no', $supplier->phone_no) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('phone_no') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="email">Email</label>
            <input type="text" id="email" name="email" class="form-control rounded" value="{{ old('email', $supplier->email) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium" for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control rounded" value="{{ old('address', $supplier->address) }}" 
                   class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                   @error('address') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        
        
      
    </form>

</div>

@endsection

@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js">

</script>




<script>




</script>






@endsection

@endsection

      
@section('js')




@endsection
