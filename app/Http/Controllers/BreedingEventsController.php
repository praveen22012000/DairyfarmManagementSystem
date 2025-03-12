<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimalType;
use App\Models\AnimalDetail;
use App\Models\User;
use App\Models\Role;
use App\Models\BreedingEvents;

class BreedingEventsController extends Controller
{
    //
    public function index()
    {
        $breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        return view('animal_breeding.index',['breedings'=>$breedings]);

    }

    public function create()
    {


        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


       $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

  
        return view('animal_breeding.create',['female_Animals'=>$female_Animals,'male_animals'=>$male_animals,'veterinarians'=>$veterinarians]);
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'female_cow_id'=>'required|exists:animal_details,id',
            'male_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',
            'breeding_date'=>'required|string',
            
            'insemination_type'=>'required|string',
            'notes'=>'required|string',
            
        ]);
     
        BreedingEvents::create([

            'female_cow_id'=>$request->female_cow_id,
            'male_cow_id'=>$request->male_cow_id,
            'veterinarian_id'=>$request->veterinarian_id,
            'breeding_date'=>$request->breeding_date,
            'insemination_type'=>$request->insemination_type,
            'notes'=>$request->notes
        ]);

        return redirect()->route('animal_breedings.list')->with('success', 'Animal Breeding Event record added successfully!');
    }

    public function edit(Request $request,BreedingEvents $animalbreeding)
    {
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();


        $Breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        
        return view('animal_breeding.edit',['Breedings'=>$Breedings,'animalbreeding'=>$animalbreeding,'male_animals'=>$male_animals,'female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians]);


    }

    public function view(Request $request,BreedingEvents $animalbreeding)
    {
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();


        $breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        
        return view('animal_breeding.view',['breedings'=>$breedings,'animalbreeding'=>$animalbreeding,'male_animals'=>$male_animals,'female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians]);
    }

    public function update(Request $request,BreedingEvents $animalbreeding)
    {

        $data=$request->validate([

            'female_cow_id'=>'required|exists:animal_details,id',
            'male_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',
            'breeding_date'=>'required|string',
            
            'insemination_type'=>'required|string',
            'notes'=>'required|string',

        ]);

        $animalbreeding->update($data);

        return redirect()->route('animal_breedings.list')->with('success', 'Animal BreedingEvents record updated successfully!');
    }

    public function destroy(BreedingEvents $animalbreeding)
    {
        $animalbreeding->delete();

        return redirect()->route('animal_breedings.list');
    }
}
