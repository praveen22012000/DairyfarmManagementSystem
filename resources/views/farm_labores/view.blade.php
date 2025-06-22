@extends('layouts.admin.master')

@section('content')

<div class="container">
    <h2>Change User Details</h2>
    <form method="POST" action="">
        @csrf
       

        <!-- Role Selection -->
        <div class="form-group">
            <label for="role_id">Role:</label>
            <select id="role_id" name="role_id" disabled class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $farmlabore->user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">

            <div id="farmlabore_fields" class="d-none" >

            <div class="form-group">
                        <label for="farm_labore_id">Farm Labore</label><br>
                            <select name="farm_labore_id" id="farm_labore_id"  class="form-control">

                                <option value="">Select Farm Labore</option>
                                @foreach($farmlabores as $farm)
                                <option value="{{  $farm->farm_labore_id }}" {{  $farmlabore->farm_labore_id ==  $farm->farm_labore_id ? 'selected' : '' }} >{{  $farmlabore->user->name }}</option>
                                @endforeach
                            
                            </select>
                @error('farm_labore_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

           
            <div class="form-group">
                <label for="farm_labore_hire_date">Hire Date</label>
                <input type="date" name="farm_labore_hire_date" value="{{ $farmlabore->farm_labore_hire_date }}" class="form-control rounded" id="farm_labore_hire_date" >
                @error('farm_labore_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           
            </div>
            
        </div>

          
            
         
        <a href="{{ route('farm_labores.list') }}" class="btn btn-info">Back</a>
           

           
      

       
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