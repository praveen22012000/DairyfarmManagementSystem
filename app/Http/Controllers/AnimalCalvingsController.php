<?php

namespace App\Http\Controllers;


use App\Models\AnimalType;
use App\Models\Role;
use App\Models\User;
use App\Models\AnimalCalvings;
use App\Models\AnimalDetail;
use App\Models\Pregnancies;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AnimalCalvingsController extends Controller
{
    //
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $animal_calvings_details=AnimalCalvings::with(['parentCow','calf'])->get();

       

        return view('animal_calving.index',['animal_calvings_details'=>$animal_calvings_details]);


    }

    public function create()
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $Child_animals = AnimalDetail::whereNotNull('sire_id')//sire_id is not null
                                ->whereNotNull('dam_id')//dam_id is not null
                                ->whereNotIn('id', AnimalCalvings::pluck('calf_id')->toArray())//id's not in the animal calvings table
                                ->get();

       $veterinarians_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

     //  dd($veterinarians_id);
        $veterinarians=User::whereIn('role_id', $veterinarians_id)->get();

        $used_pregnancies_id = AnimalCalvings::pluck('pregnancy_id');


        $pregnancies = Pregnancies::whereNotIn('id',$used_pregnancies_id)->with(['AnimalCalving','breeding_event','AnimalDetail'])->get();



        return view('animal_calving.create',['Child_animals'=>$Child_animals,'veterinarians'=>$veterinarians,'pregnancies'=>$pregnancies]);
    }

    public function store(Request $request)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'calf_id'=>'required|unique:animal_calvings,calf_id|exists:animal_details,id',
          
            'pregnancy_id' => 'required|exists:pregnancies,id|unique:animal_calvings,pregnancy_id',


            'veterinarian_id'=>'required|exists:users,id',
            'calving_date'=>'required',
            'calving_notes'=>'required'

        ]);

        $pregnancy_id = $request->pregnancy_id;
        $pregnancy = Pregnancies::with(['breeding_event','AnimalDetail','AnimalCalving'])->findOrfail($pregnancy_id);

        $calf_id = $request->calf_id;
        $calf = AnimalDetail::with(['asCalf'])->findOrfail($calf_id);

      

       $parent_male_cow_id = $pregnancy->breeding_event->male_cow_id;
       $female_cow_id = $pregnancy->breeding_event->female_cow_id;

       $parent_male_animal_name = AnimalDetail::findOrfail($parent_male_cow_id);
       $parent_female_animal_name = AnimalDetail::findOrfail($female_cow_id);

        if ($calf->dam_id != $pregnancy->breeding_event->female_cow_id) 
       {
                return back()->withInput()->withErrors([
                            'The dam of the calf "' . $calf->animal_name . '" should be "' . $parent_female_animal_name->animal_name . '", but a different animal is registered in the Animal Details table. Please correct it.'
                ]);
       }


       if ($calf->sire_id != $pregnancy->breeding_event->male_cow_id) 
       {
                return back()->withInput()->withErrors([
                            'The sire of the calf "' . $calf->animal_name . '" should be "' . $parent_male_animal_name->animal_name . '", but a different animal is registered in the Animal Details table. Please correct it.'
                ]);
       }

        if ($pregnancy->estimated_calving_date > $calf->animal_birthdate) 
        {
            return back()->withInput()->withErrors
            ([
                'The birthdate of the calf "' . $calf->animal_name . '" (' . $calf->animal_birthdate . ') cannot be earlier than the estimated calving date (' . $pregnancy->estimated_calving_date . '). Please check and update the birthdate in the Animal Details table.'
            ]);
        }

       
        
        AnimalCalvings::create([
            'calf_id'=>$request->calf_id,
       
            'pregnancy_id'=>$request->pregnancy_id,
            'veterinarian_id'=>$request->veterinarian_id,
            'calving_date'=>$request->calving_date,
            'calving_notes'=>$request->calving_notes
        ]);

        return redirect()->route('animal_calvings.list')->with('success', 'Calving record added successfully!');
    }

    public function edit(AnimalCalvings $animalcalvings)
    {
      
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

       // $Child_animals=AnimalCalvings::all();

       // Retrieve unique Child_animals (calves) from the AnimalCalvings table
        $Calf_animals = AnimalCalvings::distinct('calf_id')->with('calf')->get();


        $veterinarians_id=Role::whereIn('role_name',['Veterinarian'])->pluck('id');
        $veterinarians=User::whereIn('role_id', $veterinarians_id)->get();

        $pregnancies = Pregnancies::with(['AnimalCalving','breeding_event','AnimalDetail'])->get();
    
        return view('animal_calving.edit',['Calf_animals'=>$Calf_animals,'veterinarians'=>$veterinarians,'animalcalvings'=>$animalcalvings,'pregnancies'=>$pregnancies]);

    }

    public function update(Request $request,AnimalCalvings $animalcalvings)
    {
    
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([
            'calf_id'=>"required|unique:animal_calvings,calf_id,$animalcalvings->id",
      
            'pregnancy_id'=>"required|exists:pregnancies,id|unique:animal_calvings,pregnancy_id,$animalcalvings->id",
          
            'calving_date'=>'required',
            'veterinarian_id'=>'required',
            'calving_notes'=>'required'

        ]);

       
        
         
            // Create a new calving record
            $animalcalvings->update($data);
          
            // Redirect back with a success SweetAlert
            return redirect()->route('animal_calvings.list')->with('success', 'Calving record updated successfully!');
        
       


    }

    public function delete(AnimalCalvings $animalcalvings)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
         //this is used to get the type of the animals Heifer,and BullCalf from the animal_type table in db.pluck() method return only the animal_type_id of the animals Heifer and BullCalf
         $calf_animal_types_id= AnimalType::whereIn('animal_type',['Heifer','BullCalf'])->pluck('id');

         //$Child_animals variable contain the details of the animal which are heifer and BullCalf
         $Child_animals=AnimalDetail::whereIn('animal_type_id',$calf_animal_types_id)->get();

        return view('animal_calving.delete',['animalcalvings'=>$animalcalvings,'Child_animals'=>$Child_animals]);

    }

    public function destroy(AnimalCalvings $animalcalvings)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $animalcalvings->delete();

        return redirect()->route('animal_calvings.list');
    }

    public function view(AnimalCalvings $animalcalvings)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $Calf_animals = AnimalCalvings::distinct('calf_id')->with('calf')->get();


        
       $veterinarians_id=Role::whereIn('role_name',['Veterinarian'])->pluck('id');
       $veterinarians=User::whereIn('role_id', $veterinarians_id)->get();

       $pregnancies = Pregnancies::with(['AnimalCalving','breeding_event','AnimalDetail'])->get();

         return view('animal_calving.view',['Calf_animals'=>$Calf_animals,'veterinarians'=>$veterinarians,'animalcalvings'=>$animalcalvings,'pregnancies'=>$pregnancies]);

    }

    

    public function getCalfDetails($calfId)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        // Fetch the calf's details along with the parent cow (dam)
        $calf = AnimalDetail::with('asCalf')->find($calfId);

        
    
        if ($calf) {
            // Return the calving details as JSON
            return response()->json([
                'calving_date' => $calf->animal_birthdate, // Calving date equals birthdate
               
            ]);
        }
    
        return response()->json(['error' => 'Calf not found'], 404);
    }
}
