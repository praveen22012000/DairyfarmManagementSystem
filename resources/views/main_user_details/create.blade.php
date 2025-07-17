@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2 style="text-align:center;">User Details</h2>
    <form method="POST" action="{{ route('main_user_details.store') }}">
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
                <label for="name">First Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="lastname" id="lastname" value="{{ old('lastname') }}" name="lastname" class="form-control">
                @error('lastname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" value="{{ old('address') }}" name="address" class="form-control">
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" class="form-control">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

             <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" value="{{ old('phone_number') }}" name="phone_number" class="form-control">
                @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" value="{{ old('nic') }}" name="nic" class="form-control">
                @error('nic') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


              <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" class="form-control" required>
                            <option value="">Select the role</option>
                                @foreach ($roles as  $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                @endforeach
                    </select>
                @error('role_id')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <label for="password">Password</label>
            <div class="form-group row">
                
                <div class="col-sm-6 mb-3 mb-sm-0">
                                        
                        <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                             @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                                    
                <div class="col-sm-6">
                        <input type="password" name="password_confirmation" class="form-control form-control-user" placeholder="Repeat Password" required>
                </div>
            </div>

          
            
            <button type="submit" class="btn btn-success mt-3">Register User</button>
       

      


       
    </form>




</div>

  
      


@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   


</script>


@endsection