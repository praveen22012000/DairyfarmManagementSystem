<?php

namespace App\Http\Controllers;


use App\Models\AnimalType;
use App\Models\Role;
use App\Models\User;
use App\Models\AnimalCalvings;
use App\Models\AnimalDetail;
use App\Models\Pregnancies;

use Illuminate\Http\Request;

class AnimalCalvingsController extends Controller
{
    //
    public function index()
    {
        $animal_calvings_details=AnimalCalvings::with(['parentCow','calf'])->get();

       

        return view('animal_calving.index',['animal_calvings_details'=>$animal_calvings_details]);


    }

    public function create()
    {
        $Child_animals = AnimalDetail::whereNotNull('sire_id')
                                ->whereNotNull('dam_id')
                                ->whereNotIn('id', AnimalCalvings::pluck('calf_id')->toArray())
                                ->get();

       $veterinarians_id=Role::whereIn('role_name',['Veterinarian'])->pluck('id');
        $veterinarians=User::whereIn('role_id', $veterinarians_id)->get();


        $pregnancies = Pregnancies::with(['AnimalCalving','breeding_event','AnimalDetail'])->get();


        return view('animal_calving.create',['Child_animals'=>$Child_animals,'veterinarians'=>$veterinarians,'pregnancies'=>$pregnancies]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'calf_id'=>'required|unique:animal_calvings,calf_id|exists:animal_details,id',
          
            'pregnancy_id' => 'required|exists:pregnancies,id|unique:animal_calvings,pregnancy_id',


            'veterinarian_id'=>'required|exists:users,id',
            'calving_date'=>'required',
            'calving_notes'=>'required'

        ]);

    


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
         //this is used to get the type of the animals Heifer,and BullCalf from the animal_type table in db.pluck() method return only the animal_type_id of the animals Heifer and BullCalf
         $calf_animal_types_id= AnimalType::whereIn('animal_type',['Heifer','BullCalf'])->pluck('id');

         //$Child_animals variable contain the details of the animal which are heifer and BullCalf
         $Child_animals=AnimalDetail::whereIn('animal_type_id',$calf_animal_types_id)->get();

        return view('animal_calving.delete',['animalcalvings'=>$animalcalvings,'Child_animals'=>$Child_animals]);

    }

    public function destroy(AnimalCalvings $animalcalvings)
    {
        $animalcalvings->delete();

        return redirect()->route('animal_calvings.list');
    }

    public function view(AnimalCalvings $animalcalvings)
    {


        $Calf_animals = AnimalCalvings::distinct('calf_id')->with('calf')->get();


        
       $veterinarians_id=Role::whereIn('role_name',['Veterinarian'])->pluck('id');
       $veterinarians=User::whereIn('role_id', $veterinarians_id)->get();

       $pregnancies = Pregnancies::with(['AnimalCalving','breeding_event','AnimalDetail'])->get();

         return view('animal_calving.view',['Calf_animals'=>$Calf_animals,'veterinarians'=>$veterinarians,'animalcalvings'=>$animalcalvings,'pregnancies'=>$pregnancies]);

    }

    

    public function getCalfDetails($calfId)
    {
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
