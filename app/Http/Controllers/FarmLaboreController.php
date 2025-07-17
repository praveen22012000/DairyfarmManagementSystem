<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmLabore;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class FarmLaboreController extends Controller
{
    //
    public function view(FarmLabore $farmlabore)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::all(); // Fetch all roles

        $farmlabores=FarmLabore::with('user')->get();

     

        return view('farm_labores.view',['roles'=>$roles,'farmlabores'=>$farmlabores,'farmlabore'=>$farmlabore]);
    }

    public function edit(FarmLabore $farmlabore)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
       $roles = Role::all(); // Fetch all roles

        $farmlabores=FarmLabore::with('user')->get();

     

        return view('farm_labores.edit',['roles'=>$roles,'farmlabores'=>$farmlabores,'farmlabore'=>$farmlabore]);
    }

    public function update(Request $request,FarmLabore $farmlabore)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $data=$request->validate([
            'farm_labore_id'=>'required|exists:users,id',
            'farm_labore_hire_date'=>'required|date'
        ]);

        $farmlabore->update($data);


          return redirect()->route('farm_labores.list')->with('success', 'farm labore record updated successfully!');
    }

    public function destroy(FarmLabore $farmlabore)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $farmlabore->delete();

        return redirect()->route('farm_labores.list')->with('success','farm labore record deleted successfully!');
    }
}
