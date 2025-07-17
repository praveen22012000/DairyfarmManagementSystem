@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2 style="text-align:center">User Registration</h2>
<form method="POST" action="{{ route('users.store') }}">
   
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
                        <label for="veterinarian_id">Veterinarian Name</label><br>
                            <select name="veterinarian_id" id="veterinarian_id"  class="form-control">

                                <option value="">Select Veterinarian</option>
                                @foreach($veterinarians as $veterinarian)
                                <option value="{{ $veterinarian->id }}"
                                {{ old('veterinarian_id') == $veterinarian->id ? 'selected' : '' }}
                                >{{ $veterinarian->name }}</option>
                                @endforeach
                            
                            </select>
                @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" class="form-control" value="{{ old('specialization') }}">
                @error('specialization') <span class="text-danger">{{ $message }}</span> @enderror
            </div>




            <div class="form-group">
                <label for="doctor_hire_date">Hire Date:</label>
                <input type="date" id="doctor_hire_date" name="doctor_hire_date" value="{{ old('doctor_hire_date') }}" class="form-control">
                @error('doctor_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

        


            <div class="form-group">
                <label for="license_number">License Number:</label>
                <input type="text" id="license_number" name="license_number" placeholder="license number should be in this format SLVC-2024-0089" class="form-control">
                @error('license_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


          

           


         

        </div>


        <!-- retailor fields-->
        <div id="retailer_fields" class="d-none" >


                
            <div class="form-group">
                        <label for="retailer_id">Retailer</label><br>
                        <select name="retailer_id" id="retailer_id"  class="form-control">

                            <option value="">Select Retailer</option>
                                    @foreach($retailers as $retailer)
                            <option value="{{ $retailer->id }}"
                               {{ old('retailer_id') == $retailer->id ? 'selected' : '' }}
                            >{{ $retailer->name }}</option>
                                    @endforeach
                            
                        </select>
                        @error('retailer_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

           


            <div class="form-group">
                <label for="store_name">Store Name:</label>
                <input type="text" id="store_name" name="store_name" value="{{ old('store_name') }}" class="form-control">

                @error('store_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="business_type">Business Type:</label>
                <input type="text" id="business_type" name="business_type" value="{{ old('business_type')  }}" class="form-control">
                @error('business_type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="tax_id">Tax ID:</label>
                <input type="text" id="tax_id" name="tax_id" value="{{ old('tax_id') }}" class="form-control">
                @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            
            
        </div>


<!-- Farm labore fields -->
        <div id="farmlabore_fields" class="d-none" >

            <div class="form-group">
                        <label for="farm_labore_id">Farm Labore</label><br>
                            <select name="farm_labore_id" id="farm_labore_id"  class="form-control">

                                <option value="">Select Farm Labore</option>
                                @foreach($farm_labores as $farm_labore)
                                <option value="{{ $farm_labore->id }}"
                                  {{ old('farm_labore_id') == $farm_labore->id ? 'selected' : '' }}
                                >{{ $farm_labore->name }}</option>
                                @endforeach
                            
                            </select>
                @error('farm_labore_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

           
            <div class="form-group">
                <label for="farm_labore_hire_date">Hire Date</label>
                <input type="date" name="farm_labore_hire_date" value="{{ old('farm_labore_hire_date')  }}" class="form-control rounded" id="farm_labore_hire_date" >
                @error('farm_labore_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           
            
        </div>

<!-- general manager fields-->
         <div id="general_manager_fields" class="d-none" >

            <div class="form-group">
                        <label for="general_manager_id">General Manager</label><br>
                            <select name="general_manager_id" id="general_manager_id"  class="form-control">

                                <option value="">Select General Manager</option>
                                @foreach($general_managers as $general_manager)
                                <option value="{{ $general_manager->id }}">{{ $general_manager->name }}</option>
                                @endforeach
                            
                            </select>
                @error('general_manager_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="general_manager_hire_date">Hire Date</label>
                <input type="date" name="general_manager_hire_date" class="form-control rounded" id="general_manager_hire_date" >
                @error('general_manager_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           

            <div class="form-group">
                <label for="qualification">Qualification</label>
                <input type="text" name="qualification" class="form-control rounded" id="qualification" >
                @error('qualification') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        
           
            
        </div>



        <!-- sales manager fields-->
         <div id="sales_manager_fields" class="d-none" >

            <div class="form-group">
                        <label for="sales_manager_id">Sales Manager</label><br>
                            <select name="sales_manager_id" id="sales_manager_id"  class="form-control">

                                <option value="">Select General Manager</option>
                                @foreach($sales_managers as $sales_manager)
                                <option value="{{ $sales_manager->id }}">{{ $sales_manager->name }}</option>
                                @endforeach
                            
                            </select>
                @error('sales_manager_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="sales_manager_hire_date">Hire Date</label>
                <input type="date" name="sales_manager_hire_date" class="form-control rounded" id="sales_manager_hire_date" >
                @error('sales_manager_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           

            <div class="form-group">
                <label for="sales_manager_qualification">Qualification</label>
                <input type="text" name="sales_manager_qualification" class="form-control rounded" id="sales_manager_qualification" >
                @error('sales_manager_qualification') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
                
        
            
        </div>
    
        <button type="submit" class="btn btn-primary">Register</button>


</form>

</div>


   

  
      


@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   
<script>
function changeFields() {
    let roleId = $('#role_id').val();

    // Hide all role-specific fields initially
    $('#doctor_fields').addClass('d-none');
    $('#retailer_fields').addClass('d-none');
    $('#farmlabore_fields').addClass('d-none');
    $('#general_manager_fields').addClass('d-none');
    // You can add sales_manager_fields here if needed
    $('#sales_manager_fields').addClass('d-none');
    $('#farmowner_fields').addClass('d-none');

    // Show relevant fields based on the selected role
    if (roleId == 2) { // Doctor
        $('#doctor_fields').removeClass('d-none');
    } 
    else if (roleId == 3) { // Retailer
        $('#retailer_fields').removeClass('d-none');
    } 
    else if (roleId == 5) { // Farm Labore
        $('#farmlabore_fields').removeClass('d-none');
    }
    else if (roleId == 6) { // General Manager (example id)
        $('#general_manager_fields').removeClass('d-none');
    }
    else if (roleId == 7) { // Sales Manager (example id)
        $('#sales_manager_fields').removeClass('d-none');
    }
    else if (roleId == 8) { // Farm Owner (example id)
        $('#farmowner_fields').removeClass('d-none');
    }
}

$(document).ready(function () {
   function changeFields() {
    let roleId = $('#role_id').val();

    // Hide all role-specific fields initially
    $('#doctor_fields').addClass('d-none');
    $('#retailer_fields').addClass('d-none');
    $('#farmlabore_fields').addClass('d-none');
    $('#general_manager_fields').addClass('d-none');
    // You can add sales_manager_fields here if needed
    $('#sales_manager_fields').addClass('d-none');
    $('#farmowner_fields').addClass('d-none');

    // Show relevant fields based on the selected role
    if (roleId == 2) { // Doctor
        $('#doctor_fields').removeClass('d-none');
    } 
    else if (roleId == 3) { // Retailer
        $('#retailer_fields').removeClass('d-none');
    } 
    else if (roleId == 5) { // Farm Labore
        $('#farmlabore_fields').removeClass('d-none');
    }
    else if (roleId == 6) { // General Manager (example id)
        $('#general_manager_fields').removeClass('d-none');
    }
    else if (roleId == 7) { // Sales Manager (example id)
        $('#sales_manager_fields').removeClass('d-none');
    }
    else if (roleId == 8) { // Farm Owner (example id)
        $('#farmowner_fields').removeClass('d-none');
    }
}
    changeFields();

    $('#role_id').change( function () {
     
        changeFields();

    });

});


</script>


@endsection