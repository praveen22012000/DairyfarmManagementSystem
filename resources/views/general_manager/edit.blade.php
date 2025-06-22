@extends('layouts.admin.master')

@section('content')

<div class="container">
    <h2>Change User Details</h2>
    <form method="POST" action="{{ route('general_manager.update',$generalmanager->id) }}">
        @csrf
        <!-- Role Selection -->
        <div class="form-group">
            <label for="role_id">Role:</label>
            <select id="role_id" name="role_id" disabled class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $generalmanager->user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

       <div id="general_manager_fields" class="d-none" >

            <div class="form-group">
                        <label for="general_manager_id">General Manager</label><br>
                            <select name="general_manager_id" id="general_manager_id"  class="form-control">

                                <option value="">Select General Manager</option>
                                @foreach($general_managers as $general_man)
                                <option value="{{ $general_man->general_manager_id }}"
                                {{ $generalmanager->general_manager_id == $general_man->general_manager_id ? 'selected' : ''}}
                                >{{ $generalmanager->user->name }}</option>
                                @endforeach
                            
                            </select>
                @error('general_manager_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="general_manager_hire_date">Hire Date</label>
                <input type="date" value="{{ $generalmanager->general_manager_hire_date }}" name="general_manager_hire_date" class="form-control rounded" id="general_manager_hire_date" >
                @error('general_manager_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
           

            <div class="form-group">
                <label for="qualification">Qualification</label>
                <input type="text" value="{{ $generalmanager->qualification }}" name="qualification" class="form-control rounded" id="qualification" >
                @error('qualification') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        
           
            
        </div>

          
            
         
         <button type="submit" class="btn btn-success mt-3">Update</button>
           

           
      

       
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