<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    //

    public function index()
    {
        $appointments=Appointment::with(['user'])->get();

        return view('appointments.index',['appointments'=>$appointments]);
    }

    public function create()
    {
        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

        return view('appointments.create',['veterinarians'=>$veterinarians]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'veterinarian_id'=>'required|exists:users,id',
            'appointment_date'=>'required',
            'appointment_time'=>'required',
            'notes'=>'required'

        ]);

        Appointment::create([
            'veterinarian_id'=>$request->veterinarian_id,
            'appointment_date'=>$request->appointment_date,
            'appointment_time'=>$request->appointment_time,
            'notes'=>$request->notes
        ]);

        return redirect()->route('appointment.list')->with('success', 'Appointment record stored successfully!');
    }

    public function edit(Request $request,Appointment $appointment)
    {
        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

     
        return view('appointments.edit',['veterinarians'=>$veterinarians,'appointment'=>$appointment]);
    }

    public function update(Request $request,Appointment $appointment)
    {
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
        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

     
        return view('appointments.view',['veterinarians'=>$veterinarians,'appointment'=>$appointment]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointment.list');
    }
}
