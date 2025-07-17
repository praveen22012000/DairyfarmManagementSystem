@extends('layouts.admin.master')

@section('content')
<div class="col-md-12">
    <h1 style="text-align:center;">Staff Monthly Salary Management</h1>
    
    <br>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('monthly_salary_assign.store') }}">
        @csrf

        <div class="form-group">
            <label for="user_id">Staff</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="">Select Staff</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}
                    data-role="{{ $user->role->role_name }}" 
                    data-salary="{{ $user->role->role_salary->salary }} ?? '' "
                    >{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="role_id">Role</label>
            <input type="text" name="role_id" class="form-control rounded" id="role_id" value="{{ old('role_id') }}" readonly>
            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    
        <div class="form-group">
            <label for="salary_month" class="form-label">Salary Month</label>
            <input type="month" name="salary_month" value="{{ old('salary_month') }}" class="form-control" required>
            @error('salary_month') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
      
        <div class="form-group">
            <label for="salary">Base Salary (Rs.)</label>
            <input type="text" name="salary" class="form-control rounded" id="salary" value="{{ old('salary') }}" readonly>
            @error('salary') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="custom_salary">Custom Salary (Optional)</label>
            <input type="text" name="custom_salary" class="form-control rounded" value="{{ old('custom_salary') }}" id="custom_salary">
            @error('custom_salary') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <button type="submit" class="btn btn-success mt-3">Pay Salary</button>
    </form>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {// This waits until the entire HTML page is fully loaded before running any JavaScript inside it.

    function populateFields() //This defines a reusable function called populateFields
    {
        const selected = $('#user_id').find('option:selected');// Gets the currently selected <option> inside the <select> dropdown with ID user_id.
        const role = selected.data('role');// These lines read the data-role and data-salary attributes from the selected option.
        const salary = selected.data('salary');//These lines read the data-role and data-salary attributes from the selected option.

        $('#role_id').val(role ?? '');// Fills the role_id input field with the role name. If role is undefined or null, it fills with an empty string ('') using the null coalescing operator (??).
        $('#salary').val(salary ? 'Rs. ' + parseFloat(salary).toFixed(2) : '');// It converts it to a number using parseFloat() //Then formats it to 2 decimal places using .toFixed(2) //Adds 'Rs. ' in front (e.g., Rs. 50000.00)
    }

    $('#user_id').on('change', populateFields);// When a different user is selected from the dropdown, call populateFields() again to update the related fields.

    // Initialize fields on page load
    @if(old('user_id')) // Laravel Blade checks if an old user_id value exists from the last form submission (after validation error).//If it does, the next lines will restore the selected staff and fields.
        // Set the user first
        $('#user_id').val('{{ old('user_id') }}');//Sets the selected user in the dropdown to the one previously chosen.
        
        // Then populate other fields
        populateFields();//After setting the user, this line calls the populateFields() function again to auto-fill the role and salary fields with that user's data.
        
        // Restore custom salary if it exists
        @if(old('custom_salary'))//If the user had typed a custom salary before submission, this line restores that value into the #custom_salary input field.
            $('#custom_salary').val('{{ old('custom_salary') }}');
        @endif
    @endif
});
</script>
@endsection