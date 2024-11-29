<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Retailer;

use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    //

    public function index()
    {
        $veterinarians=Veterinarian::with('user')->get();
        

        return view('users.index',$veterinarians);
    }

    public function create()
    {
        $roles = Role::all(); // Fetch all roles

       // Get users with the veterinarian role who are not in the veterinarians table
        $veterinarians = User::where('role_id', 2) // Only veterinarians
        ->whereNotIn('id', Veterinarian::pluck('veterinarian_id')->toArray()) // Exclude already registered
        ->get();


        $retailers=User::where('role_id',3)//only retailers
        ->whereNotIn('id',Retailer::pluck('retailer_id')->toArray())
        ->get();
  

       
   
        return view('users.create', ["roles"=>$roles,"veterinarians"=>$veterinarians,'retailers'=>$retailers]);
    }

    public function store(Request $request)
    {
        if($request->role_id == 2)
        {
          

            $request->validate([
                'specialization'=>'required|string',
                'hire_date'=>'required|date',
                'birth_date'=>'required|date',
                'license_number'=>'required',
                'gender'=>'required',
                'salary'=>'required',
              
                'veterinarian_id' => 'required',


            ]);

            Veterinarian::create([

                'specialization'=>$request->specialization,
                'hire_date'=>$request->hire_date,
                'birth_date'=>$request->birth_date,
                'license_number'=>$request->license_number,
                'gender'=>$request->gender,
                'salary'=>$request->salary,
                    
                'veterinarian_id'=>$request->veterinarian_id

                ]);
        }

        else if($request->role_id == 3)
        {
           
            $request->validate([

                'retailer_id'=>'required',

                'store_name'=>'required|string',
                'business_type'=>'required|string',
                'tax_id'=>'required|string'
                    
            ]);

          
            Retailer::create([

                'retailer_id'=>$request->retailer_id ,
                'store_name'=>$request->store_name,
                'business_type'=>$request->business_type,
                'tax_id'=>$request->tax_id
                 


            ]);
        }
    }

    
}
