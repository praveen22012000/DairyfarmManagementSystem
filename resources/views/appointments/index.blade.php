@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Veterinarian Appointments</h2>
                </div>
                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('appointment.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Appointments</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Appointment ID</th>
                        <th>Veterinarian</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Notes</th>
                       
                        <th>Actions</th>
                      
                    </tr>
                </thead>
                  
                @foreach($appointments as $appointment)  
                    <tr>
                        <td>{{$appointment->id}}</td>
                        <td>{{$appointment->user->name}}</td>
                        <td>{{$appointment->appointment_date}}</td>
                      
                        <td>{{$appointment->appointment_time}}</td>
                        <td>{{$appointment->notes}}</td>

                    
                       
                        <td>
                        <a href="{{ route('appointment.view',$appointment->id) }}" class="btn btn-info">View</a>
                    
                        <a href="{{ route('appointment.edit',$appointment->id) }}" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $appointment->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
                <tbody>
            
                </tbody>
            </table>

            <form id="deleteForm" method="post" style="display:none;">
            @csrf
            @method('POST')
            </form>
            

            <div class="pt-2">
                <div class="float-right">
                   
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   function confirmDelete(appointmentId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the appointment record.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form action dynamically based on animal ID
                let deleteForm = document.getElementById("deleteForm");
                deleteForm.action = `/veterinarians_schedule/${appointmentId}/destroy`;
                deleteForm.submit();
            }
        });
    }
</script>

@endsection