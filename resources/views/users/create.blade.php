@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2>User Registration</h2>
<form method="POST" action="{{ route('users.store') }}">
   
    @csrf

        <div class="form-group">
            <label for="role_id">Role:</label>
            <select id="role_id" name="role_id" class="form-control" value="{{ old('role_id') }}">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}"{{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Doctor fileds -->
        <div id="doctor_fields" class="d-none" >

    
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" class="form-control" value="{{ old('specialization') }}">
                @error('specialization') <span class="text-danger">{{ $message }}</span> @enderror
            </div>




            <div class="form-group">
                <label for="hire_date">Hire Date:</label>
                <input type="date" id="hire_date" name="hire_date" class="form-control">
                @error('hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Date of Birth</label>
                <input type="date" name="birth_date" class="form-control rounded" id="birth_date" >
                @error('birth_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="license_number">License Number:</label>
                <input type="text" id="license_number" name="license_number" class="form-control">
                @error('license_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">

                <label for="gender">Gender</label><br>

                <input type="radio" id="male" name="gender" value="Male">
                <label for="male">Male</label><br>

                <input type="radio" id="female" name="gender" value="Female">
                <label for="female">Female</label><br>

                <input type="radio" id="others" name="gender" value="Others">
                <label for="others">Others</label>
                @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                        <label for="veterinarian_id">Veterinarian</label><br>
                            <select name="veterinarian_id" id="veterinarian_id"  class="form-control">

                                <option value="">Select Veterinarian</option>
                                @foreach($veterinarians as $veterinarian)
                                <option value="{{ $veterinarian->id }}">{{ $veterinarian->name }}</option>
                                @endforeach
                            
                            </select>
                @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="salary">Salary(Rs.)</label>
                <input type="text" name="salary" class="form-control rounded" id="salary" >
                @error('salary') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


        </div>


        <!-- retailor fields-->
        <div id="retailer_fields" class="d-none" >


                
            <div class="form-group">
                        <label for="retailer_id">Retailer</label><br>
                        <select name="retailer_id" id="retailer_id"  class="form-control">

                            <option value="">Select Retailer</option>
                                    @foreach($retailers as $retailer)
                            <option value="{{ $retailer->id }}">{{ $retailer->name }}</option>
                                    @endforeach
                            
                        </select>
                        @error('retailer_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

           


            <div class="form-group">
                <label for="store_name">Store Name:</label>
                <input type="text" id="store_name" name="store_name" class="form-control">

                @error('store_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="business_type">Business Type:</label>
                <input type="text" id="business_type" name="business_type" class="form-control">
                @error('business_type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="tax_id">Tax ID:</label>
                <input type="text" id="tax_id" name="tax_id" class="form-control">
                @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            
            
        </div>


<!-- Farm labore fields -->
        <div id="farmlabore_fields" class="d-none" >

            <div class="form-group">
                        <label for="user_id">Farm Labore</label><br>
                            <select name="user_id" id="user_id"  class="form-control">

                                <option value="">Select Farm Labore</option>
                                @foreach($farm_labores as $farm_labore)
                                <option value="{{ $farm_labore->id }}">{{ $farm_labore->name }}</option>
                                @endforeach
                            
                            </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Date of Birth</label>
                <input type="date" name="birth_date" class="form-control rounded" id="birth_date" >
                @error('birth_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
                
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" name="hire_date" class="form-control rounded" id="hire_date" >
                @error('hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           
            
        </div>


         <div id="general_manager_fields" class="d-none" >

            <div class="form-group">
                        <label for="user_id">Farm Labore</label><br>
                            <select name="user_id" id="user_id"  class="form-control">

                                <option value="">Select Farm Labore</option>
                                @foreach($farm_labores as $farm_labore)
                                <option value="{{ $farm_labore->id }}">{{ $farm_labore->name }}</option>
                                @endforeach
                            
                            </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Date of Birth</label>
                <input type="date" name="birth_date" class="form-control rounded" id="birth_date" >
                @error('birth_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
                
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" name="hire_date" class="form-control rounded" id="hire_date" >
                @error('hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           
            
        </div>

        <div id="farmlabore_fields" class="d-none" >

            <div class="form-group">
                        <label for="user_id">Farm Labore</label><br>
                            <select name="user_id" id="user_id"  class="form-control">

                                <option value="">Select Farm Labore</option>
                                @foreach($farm_labores as $farm_labore)
                                <option value="{{ $farm_labore->id }}">{{ $farm_labore->name }}</option>
                                @endforeach
                            
                            </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Date of Birth</label>
                <input type="date" name="birth_date" class="form-control rounded" id="birth_date" >
                @error('birth_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
                
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" name="hire_date" class="form-control rounded" id="hire_date" >
                @error('hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           
            
        </div>


    
        <button type="submit" class="btn btn-primary">Register</button>




</form>

</div>


   

  
      


@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   
<script>

$(document).ready(function () {
   function changeFields() {
    
        let roleId = $('#role_id').val();


        // Show relevant fields based on the selected role
        if (roleId == 3) { // Assuming '3' is Retailer

            $('#retailer_fields').removeClass('d-none');
            $('#doctor_fields').addClass('d-none');
            $('#farmlabore_fields').addClass('d-none');
        } 

        else if(roleId==2){ // Assuming '2' is Doctor
            $('#doctor_fields').removeClass('d-none');
            $('#retailer_fields').addClass('d-none');
            $('#farmlabore_fields').addClass('d-none');
          
        }

        else if(roleId==5)
        {
            $('#farmlabore_fields').removeClass('d-none');
            $('#doctor_fields').addClass('d-none');
            $('#retailer_fields').addClass('d-none');
           
        }
        // Add more conditions for other roles as needed
    }
   
    changeFields();

    $('#role_id').change( function () {
     
        changeFields();

    });

});


</script>


@endsection