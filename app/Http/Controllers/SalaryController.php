<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Salary;

use Illuminate\Http\Request;

class SalaryController extends Controller
{

    public function index()
    {
        $salaries= Salary::all();

        return view('salary.index',['salaries'=>$salaries]);
    }



    //
    public function create()
    {
        // Get the role IDs that already have a salary
    $assignedRoleIds = Salary::pluck('role_id')->toArray();

    // Exclude roles by ID (already assigned) and also by name (specific roles)
    $roles = Role::whereNotIn('id', $assignedRoleIds)
                 ->whereNotIn('id', ['1', '3', '4'])
                 ->get();

    return view('salary.create', ['roles' => $roles]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'=>'required|exists:roles,id',
            'base_salary'=> 'required',
            'effective_from'=>'required',
          

        ]);

        Salary::create([
            'role_id'=>$request->role_id,
            'base_salary'=>$request->base_salary,
            'effective_from'=>$request->effective_from,
            'effective_to'=>$request->effective_to
        ]);

        return redirect()->route('salary.list')->with('success', 'Salary assigned successfully!');
    }


     public function view(Salary $salary)
    {
        
            // Get the currently assigned labore (even if busy)
        $currentRole = Role::with('users','salary')->find($salary->role_id);

            // Get the role IDs that already have a salary
        $assignedRoleIds = Salary::pluck('role_id')->toArray();

            // Exclude roles by ID (already assigned) and also by name (specific roles)
        $roles = Role::whereNotIn('id', $assignedRoleIds)
                 ->whereNotIn('id', ['1', '3', '4'])
                 ->get();

    
        $available_roles=$roles;

         if ($currentRole && !$available_roles->contains('id', $currentRole->id)) 
        {
            $available_roles->push($currentRole);
        }

        return view('salary.view',['salary'=>$salary,'available_roles'=>$available_roles]);
        
    }


    public function edit(Salary $salary)
    {
        
            // Get the currently assigned labore (even if busy)
        $currentRole = Role::with('users','salary')->find($salary->role_id);

            // Get the role IDs that already have a salary
        $assignedRoleIds = Salary::pluck('role_id')->toArray();

            // Exclude roles by ID (already assigned) and also by name (specific roles)
        $roles = Role::whereNotIn('id', $assignedRoleIds)
                 ->whereNotIn('id', ['1', '3', '4'])
                 ->get();

    
        $available_roles=$roles;

         if ($currentRole && !$available_roles->contains('id', $currentRole->id)) 
        {
            $available_roles->push($currentRole);
        }

        return view('salary.edit',['salary'=>$salary,'available_roles'=>$available_roles]);
        
    }

    public function update(Salary $salary,Request $request)
    {
        $data=$request->validate([
            'role_id'=>'required|exists:roles,id',
            'base_salary'=>'required',
            'effective_from'=>'required',
            
        ]);

        $salary->update($data);

        // Redirect back with a success SweetAlert
        return redirect()->route('salary.list')->with('success', 'Salary record updated successfully!');
        
    }
}
