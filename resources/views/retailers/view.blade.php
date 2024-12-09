@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2>User Registration</h2>
<form method="POST" action="">
   
    @csrf


        <div class="form-group">
            <label for="role_id">Role:</label>
            <select id="role_id" name="role_id" class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $retailer->user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}</option>
                @endforeach
            </select>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

          
        <div id="retailer_fields" class="{{ $retailer->role_id == 3 ? '' : 'd-none' }}" >


                
            <div class="form-group">
                        <label for="retailer_id">Retailer</label><br>
                        <select name="retailer_id" id="retailer_id"  class="form-control">

                            <option value="">Select Retailer</option>
                                    @foreach($retailers as $ret)
                            <option value="{{ $ret->retailer_id }}"
                            {{$retailer->retailer_id==$ret->retailer_id ? 'selected':''}}
                            >{{ $ret->user->name }}</option>
                                    @endforeach
                            
                        </select>
                        @error('retailer_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

           
            <div class="form-group">
                <label for="store_name">Store Name:</label>
                <input type="text" id="store_name" name="store_name" class="form-control" value="{{$retailer->store_name}}">
                @error('store_namee') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="business_type">Business Type:</label>
                <input type="text" id="business_type" name="business_type" class="form-control" value="{{$retailer->business_type}}">
                @error('business_type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="tax_id">Tax ID:</label>
                <input type="text" id="tax_id" name="tax_id" class="form-control" value="{{$retailer->tax_id}}">
                @error('tax_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            
            
        </div>

    
        <a href="{{ route('retailers.list') }}" class="btn btn-info">Back</a>




</form>

</div>


   



@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   
<script>

$(document).ready(function () {
    // Show role-specific fields on page load (for editing)
    toggleRoleFields($('#role_id').val());

   

    // Show role-specific fields when the role changes
    $('#role_id').change(function () {
        toggleRoleFields(this.value);
    });

    // Function to show/hide fields based on the selected role
    function toggleRoleFields(roleId) {
        if (roleId == 3) { // Assuming '3' is Retailer
            $('#retailer_fields').removeClass('d-none');
            $('#doctor_fields').addClass('d-none');
        } else if (roleId == 2) { // Assuming '2' is Doctor
            $('#doctor_fields').removeClass('d-none');
            $('#retailer_fields').addClass('d-none');
        } else {
            $('#doctor_fields').addClass('d-none');
            $('#retailer_fields').addClass('d-none');
        }
    }
});
</script>


@endsection