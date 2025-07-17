<?php

namespace App\Http\Controllers;

use App\Models\Veterinarian;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VeterinarianController extends Controller
{
    //

    public function edit(Veterinarian $veterinarian)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $roles = Role::all(); // Fetch all roles

        $veterinarians=Veterinarian::with('user')->get();
  
        return view('veterinarians.edit',['veterinarian'=>$veterinarian,'roles'=>$roles,'veterinarians'=>$veterinarians]);
    }

    public function update(Request $request,Veterinarian $veterinarian)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate
        ([

                'specialization' => 'required|string',
                'doctor_hire_date' => 'required|date',

                'license_number' => [
                                        'required',
                                        'regex:/^SLVC-\d{4}-\d{4}$/',
                                        Rule::unique('veterinarians', 'license_number')->ignore($veterinarian->id),
                                    ],

                'veterinarian_id' => 'required|exists:users,id',
        ],
    
        [
                        'license_number.regex' => 'The license number must be in the format SLVC-2024-0089.',
                        'license_number.required' => 'The license number is required.',
                        'license_number.unique' => 'This license number is already in use.',
        ]
    
        );


      
          
        
        $veterinarian->update($data);

        return redirect()->route('veterinarians.list')->with('success', 'Veterinarian record updated successfully!');
    }

    public function view(Veterinarian $veterinarian)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::all(); // Fetch all roles

        $veterinarians=Veterinarian::with('user')->get();

        return view('veterinarians.view',['roles'=>$roles,'veterinarians'=>$veterinarians,'veterinarian'=>$veterinarian]);
    }

    public function destroy(Veterinarian $veterinarian)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $veterinarian->delete();
        
        return redirect()->route('veterinarians.list');
    }
}
