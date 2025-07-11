@extends('layouts.admin.master')

@section('content')
       
<div class="container mt-4">
    <h2 style="text-align:center">Change Password</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.change') }}">
        @csrf

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-2">Update Password</button>
    </form>
</div>



@endsection

@section('js')



@endsection