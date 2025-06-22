<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesManager;
use App\Models\Role;

class SalesManagerController extends Controller
{
    //

      public function view(SalesManager $salesmanager)
    {
        
        $roles = Role::all(); // Fetch all roles

        $sales_managers=SalesManager::with('user')->get();

       
        return view('sales_manager.view',['roles'=>$roles,'sales_managers'=>$sales_managers,'salesmanager'=>$salesmanager]);
    }

     public function edit(SalesManager $salesmanager)
    {
          $roles = Role::all(); // Fetch all roles

        $sales_managers=SalesManager::with('user')->get();

        
        return view('sales_manager.edit',['roles'=>$roles,'sales_managers'=>$sales_managers,'salesmanager'=>$salesmanager]);
    }

    public function update(Request $request,SalesManager $salesmanager)
    {
        $data=$request->validate([
            'sales_manager_id'=>'required|exists:users,id',
            'sales_manager_qualification'=>'required',
            'sales_manager_hire_date'=>'required|date',

        ]);

        $salesmanager->update($data);

          return redirect()->route('sales_manager.list')->with('success', 'sales manager record updated successfully!');
    }


}
