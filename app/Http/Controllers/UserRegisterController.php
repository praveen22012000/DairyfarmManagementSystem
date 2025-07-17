<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Retailer;
use App\Models\FarmLabore;
use App\Models\GeneralManager;
use App\Models\SalesManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $veterinarians=Veterinarian::all();

        $numberOfVeterinarians = $veterinarians->count();

        $retailers=Retailer::all();

        $numberOfRetailers= $retailers->count();

        $farm_labores=FarmLabore::all();

        $numberOfFarmLabores = $farm_labores->count();

        $general_managers=GeneralManager::all();

        $numberOfGeneralManagers = $general_managers->count();

        $sales_managers=SalesManager::all();

        $numberOfSalesManagers= $sales_managers->count();


        return view('users.index',['numberOfVeterinarians'=>$numberOfVeterinarians,'numberOfRetailers'=>$numberOfRetailers,'numberOfFarmLabores'=>$numberOfFarmLabores,'general_managers'=>$general_managers,'numberOfGeneralManagers'=>$numberOfGeneralManagers,'numberOfSalesManagers'=>$numberOfSalesManagers]);

       
    }

    public function veterinarian_index()
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $veterinarians=Veterinarian::with('user')->get();
  
   

        return view('veterinarians.index',['veterinarians'=>$veterinarians]);
        
    }

    public function retailer_index()
    {
          if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $retailers=Retailer::with('user')->get();

        return view('retailers.index',['retailers'=>$retailers]);
        
    }

    public function farm_labores_index()
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $farm_labores= FarmLabore::with('user')->get();
        
        return view('farm_labores.index',['farm_labores'=>$farm_labores]);
    }

    public function general_manager_index()
    {
          if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $general_managers= GeneralManager::with('user')->get();

        return view('general_manager.index',['general_managers'=>$general_managers]);
    }

    public function sales_manager_index()
    {
          if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $sales_managers= SalesManager::with('user')->get();

        return view('sales_manager.index',['sales_managers'=>$sales_managers]);
    }

    public function create()
    {
          if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

       // $roles = Role::all(); // Fetch all roles

        $roles = Role::whereNotIn('id',[1,4])->get();

       

       // Get users with the veterinarian role who are not in the veterinarians table
        $veterinarians = User::where('role_id', 2) // Only veterinarians
        ->whereNotIn('id', Veterinarian::pluck('veterinarian_id')->toArray()) // Exclude already registered
        ->get();


        $retailers=User::where('role_id',3)//only retailers
        ->whereNotIn('id',Retailer::pluck('retailer_id')->toArray())
        ->get();
  
        $farm_labores=User::where('role_id',5)//only farm labore
        ->whereNotIn('id',FarmLabore::pluck('farm_labore_id')->toArray())
        ->get();
  
       $general_managers=User::where('role_id',6)
       ->whereNotIn('id',GeneralManager::pluck('general_manager_id')->toArray())
       ->get();
   

         $sales_managers=User::where('role_id',7)
       ->whereNotIn('id',SalesManager::pluck('sales_manager_id')->toArray())
       ->get();
   

        return view('users.create', ["roles"=>$roles,"veterinarians"=>$veterinarians,'retailers'=>$retailers,'farm_labores'=>$farm_labores,'general_managers'=>$general_managers,'sales_managers'=>$sales_managers]);
    }

    public function store(Request $request)
    {
          if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        if($request->role_id == 2)
        {
        

            if ($request->role_id == 2) 
            {
                $request->validate([
                        'specialization' => 'required|string',
                        'doctor_hire_date' => 'required|date|before_or_equal:today',
                        'license_number' => [
                                            'required',
                                            'regex:/^SLVC-\d{4}-\d{4}$/',
                                            'unique:veterinarians,license_number',
                                            ],
                                            'veterinarian_id' => 'required|exists:users,id',
                ], [
                        'license_number.regex' => 'The license number must be in the format SLVC-2024-0089.',
                        'license_number.required' => 'The license number is required.',
                        'license_number.unique' => 'This license number is already in use.',
                ]);
            }
            Veterinarian::create([

                'specialization'=>$request->specialization,
                'doctor_hire_date'=>$request->doctor_hire_date,
               
                'license_number'=>$request->license_number,
                
                    
                'veterinarian_id'=>$request->veterinarian_id

                ]);

                return redirect()->route('veterinarians.list')->with('success', 'Veterinarian record added successfully!');
        }

        else if($request->role_id == 3)
        {
             if (!in_array(Auth::user()->role_id, [1])) 
            {
            abort(403, 'Unauthorized action.');
            }

            $request->validate
            ([

                'retailer_id'=>'required',

                'store_name'=>'required|string|unique:retailers,store_name',
                'business_type'=>'required|string',
                'tax_id'=>
                [
                            'required',
                            'string',
                            'regex:/^TIN-\d{8}$/',
                            'unique:retailers,tax_id'
                ]
                
                    
            ],
        
            ['tax_id.regex'=> 'The tax_id is must be in the format TIN-12345678']
            );

          
            Retailer::create([

                'retailer_id'=>$request->retailer_id ,
                'store_name'=>$request->store_name,
                'business_type'=>$request->business_type,
                'tax_id'=>$request->tax_id
                 


            ]);

            return redirect()->route('retailers.list')->with('success', 'Retailor record added successfully!');
        }


        else if($request->role_id == 5)
        {
              if (!in_array(Auth::user()->role_id, [1])) 
              {
                    abort(403, 'Unauthorized action.');
              }

           
            $request->validate([

                'farm_labore_id'=>'required',
                
                'farm_labore_hire_date'=>'required'         
            ]);

          
            FarmLabore::create([

                'farm_labore_id'=>$request->farm_labore_id ,
                
                'farm_labore_hire_date'=>$request->farm_labore_hire_date,
               
                 


            ]);

            return redirect()->route('farm_labores.list')->with('success', 'FarmLabore record added successfully!');
        }

        else if($request->role_id==6)
        {
             if (!in_array(Auth::user()->role_id, [1])) 
              {
                    abort(403, 'Unauthorized action.');
              }
           
            $request->validate
            ([
                'general_manager_id'=>'required|exists:users,id',
                'qualification'=>'required',
             
                'general_manager_hire_date'=>'required|date',
              
            ]);

           
            
      

          

             //  Now create the record with extracted values
            GeneralManager::create([
                'general_manager_id' => $request->general_manager_id,
                'general_manager_hire_date'=> $request->general_manager_hire_date,
                'qualification' => $request->qualification,
              
            ]);
               return redirect()->route('general_manager.list')->with('success', 'general manager record added successfully!');
        }


        else if($request->role_id==7)
        {
             if (!in_array(Auth::user()->role_id, [1])) 
              {
                    abort(403, 'Unauthorized action.');
              }
              
            $request->validate
            ([
                'sales_manager_id'=>'required|exists:users,id',
                'sales_manager_qualification'=>'required',
             
                'sales_manager_hire_date'=>'required|date',
              
            ]);

           
           
            
        
        
             //  Now create the record with extracted values
            SalesManager::create([
                'sales_manager_id' => $request->sales_manager_id,
                'sales_manager_hire_date'=> $request->sales_manager_hire_date,
                'sales_manager_qualification' => $request->sales_manager_qualification,
               
            ]);
               return redirect()->route('sales_manager.list')->with('success', 'sales manager record added successfully!');
        }
       
    }

    
}
