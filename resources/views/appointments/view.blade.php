@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

<h1 style="text-align:center;">Veterinarian Appointment</h1>
<br>
<form action="" method="POST">
    @csrf

    <div class="form-group">

    <label for="veterinarian_id">Select Veterinarian:</label>
    <select name="veterinarian_id" id="veterinarian_id"  class="form-control">
            <option value="">Select Veterinarian</option>
        @foreach($veterinarians as $vet)
            <option value="{{ $vet->id }}"
           {{ $appointment->veterinarian_id == $vet->id ? 'selected' : ''}}
            >{{ $vet->name }}</option>
        @endforeach
    </select>
    @error('veterinarian_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="appointment_date">Schedule Date:</label>
        <input type="date" name="appointment_date" value="{{$appointment->appointment_date}}" class="form-control rounded">
        @error('appointment_date') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="appointment_time">Schedule Time:</label>
        <input type="time" name="appointment_time" value="{{$appointment->appointment_time}}" class="form-control rounded">
        @error('appointment_time') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
            <label for="notes">Schedule notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter the appointment notes here">{{$appointment->notes}}</textarea>
            @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <a href="{{ route('appointment.list') }}" class="btn btn-info">Back</a>
   
</form>

       
</div>

@endsection
