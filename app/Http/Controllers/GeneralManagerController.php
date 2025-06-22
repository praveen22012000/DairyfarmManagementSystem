<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralManager;
use App\Models\Role;

class GeneralManagerController extends Controller
{
    //
    
    public function view(GeneralManager $generalmanager)
    {
        
        $roles = Role::all(); // Fetch all roles

        $general_managers=GeneralManager::with('user')->get();

        
        return view('general_manager.view',['roles'=>$roles,'general_managers'=>$general_managers,'generalmanager'=>$generalmanager]);
    }

    public function edit(GeneralManager $generalmanager)
    {
          $roles = Role::all(); // Fetch all roles

        $general_managers=GeneralManager::with('user')->get();

        
        return view('general_manager.edit',['roles'=>$roles,'general_managers'=>$general_managers,'generalmanager'=>$generalmanager]);
    }

    public function update(Request $request,GeneralManager $generalmanager)
    {
        $data=$request->validate([
            'general_manager_id'=>'required|exists:users,id',
            'qualification'=>'required',
            'general_manager_hire_date'=>'required|date',

        ]);

        $generalmanager->update($data);

          return redirect()->route('general_manager.list')->with('success', 'retailer record updated successfully!');
    }

    
}
