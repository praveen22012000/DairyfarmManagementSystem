@extends('layouts.admin.master')

@section('content')

<div class="col-md-12">

       
            <h1 style="text-align:center;">Designation Salary Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="">
        @csrf

       


        <div class="form-group">
        <label for="role_id">Role Name</label>
        <select name="role_id" id="role_id" class="form-control" >
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                {{ $rolesalary->role_id == $role->id ? 'selected':'' }}
                >{{ $role->role_name }}</option>
            @endforeach
        </select>
        @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    

      


        
        <!--this is get the animal_calving date-->
        <div class="form-group">
            <label for="salary">Base Salary (Rs.)</label>
                <input type="text" name="salary" class="form-control rounded" id="salary" value="{{ $rolesalary->salary }}"  required>
                 @error('salary') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        </fieldset>

        
       
    </form>

</div>



@endsection

@section('js')



@endsection