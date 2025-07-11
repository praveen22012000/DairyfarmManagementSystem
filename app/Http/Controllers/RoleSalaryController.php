<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RoleSalary;

class RoleSalaryController extends Controller
{
    //


    public function  index()
    {
        $role_salary= RoleSalary::all();

        return view('role_salaries.index',['role_salary'=>$role_salary]);
    }


    public function create()
    {
        $targetRoleIds = [2, 5, 6, 7];

        // Step 1: Get role_ids that already exist in the RoleSalary table
            $existingRoleSalaryIds = RoleSalary::whereIn('role_id', $targetRoleIds)->pluck('role_id');

            $roles = Role::whereIn('id', $targetRoleIds)
                    ->whereNotIn('id', $existingRoleSalaryIds)
                    ->get();


            return view('role_salaries.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'=>'required|exists:roles,id',
            'salary'=>'required|numeric|min:1'
        ],
        [
        'salary.min' => 'Salary should be a positive number.', //  Custom message here
        ]);

        RoleSalary::create([
            'role_id'=>$request->role_id,
            'salary'=>$request->salary
        ]);

         return redirect()->route('role_salary.list')->with('success', 'Salary record saved successfully!');
    }

    public function edit(RoleSalary $rolesalary)
    {
       
        $role_id = RoleSalary::pluck('role_id');

 

        $roles = Role::whereIn('id',$role_id)->get();
    
        return view('role_salaries.edit',['roles'=>$roles,'rolesalary'=>$rolesalary]) ;
    }

    public function update(RoleSalary $rolesalary,Request $request)
    {
        $data=$request->validate([
            'role_id'=>'required|exists:roles,id',
            'salary'=>'required|numeric|min:1'
        ]);

        $rolesalary->update($data);

          
            // Redirect back with a success SweetAlert
            return redirect()->route('role_salary.list')->with('success', 'Salary record updated successfully!');
    }

    public function view(RoleSalary $rolesalary)
    {
       
        $role_id = RoleSalary::pluck('role_id');

 

        $roles = Role::whereIn('id',$role_id)->get();
    
        return view('role_salaries.view',['roles'=>$roles,'rolesalary'=>$rolesalary]) ;
    }
}
