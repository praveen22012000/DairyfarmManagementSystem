<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesManager;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class SalesManagerController extends Controller
{
    //

      public function view(SalesManager $salesmanager)
      {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $roles = Role::all(); // Fetch all roles

        $sales_managers=SalesManager::with('user')->get();

       
        return view('sales_manager.view',['roles'=>$roles,'sales_managers'=>$sales_managers,'salesmanager'=>$salesmanager]);
      }

     public function edit(SalesManager $salesmanager)
      {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

          $roles = Role::all(); // Fetch all roles

        $sales_managers=SalesManager::with('user')->get();

        
        return view('sales_manager.edit',['roles'=>$roles,'sales_managers'=>$sales_managers,'salesmanager'=>$salesmanager]);
      }

    public function update(Request $request,SalesManager $salesmanager)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $data=$request->validate([
            'sales_manager_id'=>'required|exists:users,id',
            'sales_manager_qualification'=>'required',
            'sales_manager_hire_date'=>'required|date',

        ]);

        $salesmanager->update($data);

          return redirect()->route('sales_manager.list')->with('success', 'sales manager record updated successfully!');
    }


}
