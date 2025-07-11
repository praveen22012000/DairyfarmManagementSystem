@extends('layouts.admin.master')

@section('content')
       
<div class="container mt-4">
    <h2>Edit Profile</h2>

    <form method="POST" action="{{ route('my_profile.update') }}">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>NIC</label>
            <input type="text" name="nic" value="{{ old('nic', $user->nic) }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Emaill</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control">
        </div>

      
       
        <button type="submit" class="btn btn-success mt-2">Save Changes</button>
    </form>
</div>



@endsection

@section('js')



@endsection