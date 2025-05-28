<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Retailer;
use App\Models\FarmLabore;

use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    //

    public function index()
    {
        
        $veterinarians=Veterinarian::all();

        $numberOfVeterinarians = $veterinarians->count();

        $retailers=Retailer::all();

        $numberOfRetailers= $retailers->count();

        return view('users.index',['numberOfVeterinarians'=>$numberOfVeterinarians,'numberOfRetailers'=>$numberOfRetailers]);

       
    }

    public function veterinarian_index()
    {

        $veterinarians=Veterinarian::with('user')->get();
  
   

        return view('veterinarians.index',['veterinarians'=>$veterinarians]);
        
    }

    public function retailer_index()
    {
        $retailers=Retailer::with('user')->get();

        return view('retailers.index',['retailers'=>$retailers]);
        
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
  
        $farm_labores=User::where('role_id',5)//only farm labore
        ->whereNotIn('id',FarmLabore::pluck('user_id')->toArray())
        ->get();
  
       
   
        return view('users.create', ["roles"=>$roles,"veterinarians"=>$veterinarians,'retailers'=>$retailers,'farm_labores'=>$farm_labores]);
    }

    public function store(Request $request)
    {
        if($request->role_id == 2)
        {
          

            $request->validate([
                'specialization'=>'required|string',
                'hire_date'=>'required|date',
                'birth_date'=>'required|date',
                'license_number'=>'required|unique:veterinarians,license_number',
                'gender'=>'required',
                'salary'=>'required',
              
                'veterinarian_id' => 'required|exists:users,id'


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

                return redirect()->route('veterinarians.list')->with('success', 'Veterinarian record added successfully!');
        }

        else if($request->role_id == 3)
        {
           
            $request->validate([

                'retailer_id'=>'required',

                'store_name'=>'required|string',
                'business_type'=>'required|string',
                'tax_id'=>'required|string|unique:retailers,tax_id'
                    
            ]);

          
            Retailer::create([

                'retailer_id'=>$request->retailer_id ,
                'store_name'=>$request->store_name,
                'business_type'=>$request->business_type,
                'tax_id'=>$request->tax_id
                 


            ]);

            return redirect()->route('retailers.list')->with('success', 'Veterinarian record added successfully!');
        }


        else if($request->role_id == 5)
        {
           
            $request->validate([

                'user_id'=>'required|exists:users,id',

                'birth_date'=>'required',
                'hire_date'=>'required'
                
                    
            ]);

          
            FarmLabore::create([

                'user_id'=>$request->user_id ,
                'hire_date'=>$request->hire_date,
                'birth_date'=>$request->birth_date,
               
                 


            ]);

          //  return redirect()->route('retailers.list')->with('success', 'Veterinarian record added successfully!');
        }
    }

    
}
