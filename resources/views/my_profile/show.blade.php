@extends('layouts.admin.master')

@section('content')
       
<div class="profile-card">
    <!-- Add Edit Button at the top right -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="profile-title">User Profile</h3>
        <a href="{{ route('my_profile.edit') }}" class="btn btn-edit">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </a>
    </div>

    <table class="profile-table">
        <tr>
            <th>Name:</th>
            <td>{{ $user->name }} {{ $user->lastname }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>NIC:</th>
            <td>{{ $user->nic }}</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td>{{ $user->address }}</td>
        </tr>
        <tr>
            <th>Phone:</th>
            <td>{{ $user->phone_number }}</td>
        </tr>
        <tr>
            <th>Birthdate:</th>
            <td>{{ $user->birthdate }}</td>
        </tr>
        <tr>
            <th>Gender:</th>
            <td>{{ $user->gender }}</td>
        </tr>
        <tr>
            <th>Role:</th>
            <td>{{ $user->role->role_name }}</td>
        </tr>
    </table>
</div>

<style>
    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        padding: 25px;
        max-width: 700px;
        margin: 20px auto;
        border: 1px solid #e0e6ed;
    }

    .profile-title {
        color: #2b6cb0;
        font-weight: 600;
        margin: 0;
    }

    .btn-edit {
        background-color: #4299e1;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-edit:hover {
        background-color: #3182ce;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(66, 153, 225, 0.3);
    }

    .profile-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .profile-table tr:not(:last-child) {
        border-bottom: 1px solid #f0f4f8;
    }

    .profile-table th {
        text-align: left;
        padding: 12px 15px;
        width: 30%;
        color: #4a5568;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: top;
    }

    .profile-table td {
        padding: 12px 15px;
        color: #2d3748;
        font-size: 0.95rem;
        vertical-align: top;
    }

    .profile-table tr:hover {
        background-color: #f8fafc;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-card {
            padding: 15px;
        }
        
        .profile-table th, 
        .profile-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }
        
        .profile-table th {
            width: 35%;
        }

        .d-flex {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-edit {
            margin-top: 10px;
            width: 100%;
            justify-content: center;
        }
    }

    /* Optional: Add some dairy farm theme colors */
    .profile-table tr:first-child th,
    .profile-table tr:first-child td {
        color: #2b6cb0; /* Dairy blue */
    }

    .profile-table tr:nth-child(odd) {
        background-color: #f7fafc; /* Very light blue */
    }
</style>

@endsection

@section('js')



@endsection