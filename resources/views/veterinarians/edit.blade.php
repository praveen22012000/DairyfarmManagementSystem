@extends('layouts.admin.master')

@section('content')

<div class="container">
    <h2 style="text-align:center;">Change User Details</h2>
    <form method="POST" action="{{ route('veterinarians.update',$veterinarian->id)}}">
        @csrf
       

        <!-- Role Selection -->
        <div class="form-group">
            <label for="role_id">Role:</label>
            <select id="role_id" name="role_id" disabled class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $veterinarian->user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

         <div class="form-group">

                <label for="veterinarian_id">Veterinarian</label><br>
                            <select name="veterinarian_id" id="veterinarian_id"  class="form-control">

                                <option value="">Select Veterinarian</option>
                                @foreach($veterinarians as $vet)
                        <option value="{{ $vet->veterinarian_id }}" {{ $veterinarian->veterinarian_id == $vet->veterinarian_id ? 'selected' : '' }}>
                            {{ $vet->user->name }}
                        </option>
                    @endforeach
                            </select>
                            @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            

        <!-- Veterinarian Fields -->
        <div id="doctor_fields" class="{{ $veterinarian->role_id == 2 ? '' : 'd-none' }}">
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" class="form-control" 
                       value="{{ $veterinarian->specialization }}">
                       @error('specialization') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="doctor_hire_date">Hire Date:</label>
                <input type="date" id="doctor_hire_date" name="doctor_hire_date" class="form-control" 
                       value="{{ $veterinarian->doctor_hire_date }}">
                       @error('doctor_hire_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

          
            <div class="form-group">
                <label for="license_number">License Number:</label>
                <input type="text" id="license_number" name="license_number" class="form-control" 
                       value="{{ $veterinarian->license_number }}">
                       @error('license_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

         

           

           
        </div>

        <button type="submit" class="btn btn-success mt-3">Update</button>

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
        if (roleId == 3) { // Assuming '1' is Retailer

            $('#retailer_fields').removeClass('d-none');
            $('#doctor_fields').addClass('d-none');
          
        } 

        else if(roleId==2){ // Assuming '2' is Doctor
            $('#doctor_fields').removeClass('d-none');
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