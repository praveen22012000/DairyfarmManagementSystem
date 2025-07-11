@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2>User Details</h2>
    <br>
    <form method="POST" action="{{ route('main_user_details.update',$user->id) }}">
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
                    <label for="role_id">Role</label>
           
                        <!-- this is used to list the animal types-->
                <select name="role_id" id="role_id" class="form-control" >
                        <option value="">Select Role</option>
             
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                            {{ $user->role_id == $role->id ? 'selected' : '' }}
                            >{{ $role->role_name}}</option>
                        @endforeach
                </select>
                    @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="name">First Name:</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ $user->name  }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="lastname" id="lastname" name="lastname" class="form-control" 
                       value="{{ $user->lastname }}">
                @error('lastname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" 
                       value="{{ $user->address }}">
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="form-control" 
                       value="{{ $user->email  }}">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Phone No</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" 
                       value="{{ $user->phone_number  }}">
                @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" name="nic" class="form-control" 
                       value="{{ $user->nic }}">
                @error('nic') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

          
            

    


            <button type="submit" class="btn btn-success mt-3">Update</button>


       
    </form>




</div>

  
      


@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   


</script>


@endsection