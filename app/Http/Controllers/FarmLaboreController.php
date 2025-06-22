<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmLabore;
use App\Models\Role;

class FarmLaboreController extends Controller
{
    //
      public function view(FarmLabore $farmlabore)
    {
        
        $roles = Role::all(); // Fetch all roles

        $farmlabores=FarmLabore::with('user')->get();

     

        return view('farm_labores.view',['roles'=>$roles,'farmlabores'=>$farmlabores,'farmlabore'=>$farmlabore]);
    }

    public function edit(FarmLabore $farmlabore)
    {
       $roles = Role::all(); // Fetch all roles

        $farmlabores=FarmLabore::with('user')->get();

     

        return view('farm_labores.edit',['roles'=>$roles,'farmlabores'=>$farmlabores,'farmlabore'=>$farmlabore]);
    }

    public function update(Request $request,FarmLabore $farmlabore)
    {
        $data=$request->validate([
            'farm_labore_id'=>'required|exists:users,id',
            'farm_labore_hire_date'=>'required|date'
        ]);

        $farmlabore->update($data);


          return redirect()->route('farm_labores.list')->with('success', 'farm labore record updated successfully!');
    }
}
