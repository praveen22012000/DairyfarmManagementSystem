<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VeterinarianAppointmentNotification;


class AppointmentController extends Controller
{
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $appointments=Appointment::with(['user'])->get();

        return view('appointments.index',['appointments'=>$appointments]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

 

        return view('appointments.create',['veterinarians'=>$veterinarians]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'veterinarian_id'=>'required|exists:users,id',
            'appointment_date'=>'required|after_or_equal:today',
            'appointment_time'=>'required',
            'notes'=>'required'

        ]);

        
          $exists = Appointment::where('veterinarian_id',$request->veterinarian_id)
            ->where('appointment_date', $request->appointment_date)
              ->where('appointment_time', $request->appointment_time)
              ->exists();

    if ($exists) 
    {
        return redirect()->back()
               ->withInput()
               ->withErrors(['This time and date is already appointed to this veterinarian']);
    }

        $appointment=Appointment::create([
            'veterinarian_id'=>$request->veterinarian_id,
            'appointment_date'=>$request->appointment_date,
            'appointment_time'=>$request->appointment_time,
            'notes'=>$request->notes
        ]);

        Mail::to('pararajasingampraveen22@gmail.com')->send(new VeterinarianAppointmentNotification($appointment));

        return redirect()->route('appointment.list')->with('success', 'Appointment record stored successfully!');
    }

    public function edit(Request $request,Appointment $appointment)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

     
        return view('appointments.edit',['veterinarians'=>$veterinarians,'appointment'=>$appointment]);
    }

    public function update(Request $request,Appointment $appointment)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $data=$request->validate([
            'veterinarian_id'=>'required|exists:users,id',
            'appointment_date'=>'required',
            'appointment_time'=>'required',
            'notes'=>'required'

        ]);

        $appointment->update($data);

        return redirect()->route('appointment.list')->with('success', 'Appointment record updated successfully!');
    }

    public function view(Request $request,Appointment $appointment)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

     
        return view('appointments.view',['veterinarians'=>$veterinarians,'appointment'=>$appointment]);
    }

    public function destroy(Appointment $appointment)
    {
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $appointment->delete();

        return redirect()->route('appointment.list');
    }
}
