<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimalType;
use App\Models\AnimalDetail;
use App\Models\User;
use App\Models\Role;
use App\Models\BreedingEvents;
use Illuminate\Support\Facades\Auth;

class BreedingEventsController extends Controller
{
    //
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        return view('animal_breeding.index',['breedings'=>$breedings]);

    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }



        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


       $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

  
        return view('animal_breeding.create',['female_Animals'=>$female_Animals,'male_animals'=>$male_animals,'veterinarians'=>$veterinarians]);
    }

    public function store(Request $request)
    {

        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'female_cow_id'=>'required|exists:animal_details,id',
            'male_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',
            'breeding_date'=>'required|string|before_or_equal:today',
            
            'insemination_type'=>'required|string',
            'notes'=>'required|string',
            
        ]);

        $female = AnimalDetail::find($request->female_cow_id);
        $male = AnimalDetail::find($request->male_cow_id);

        if ($request->breeding_date <= $female->animal_birthdate) 
        {
             return redirect()->back()
           ->withInput()

           ->withErrors(['the breeding date should not before the female animal birthdate'.$female->animal_birthdate.'.']);
        }

        if ($request->breeding_date <= $male->animal_birthdate) 
        {
             return redirect()->back()
           ->withInput()
           ->withErrors(['The breeding date should not before the male_animal birthdate'.$male->animal_birthdate.'.']);
        }
     
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
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


        $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();


        $Breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        
        return view('animal_breeding.edit',['Breedings'=>$Breedings,'animalbreeding'=>$animalbreeding,'male_animals'=>$male_animals,'female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians]);


    }

    public function view(Request $request,BreedingEvents $animalbreeding)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                        ->where('status','alive')
                                        ->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();


        $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

        $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();


        $breedings=BreedingEvents::with(['user','malecow','femalecow'])->get();

        
        return view('animal_breeding.view',['breedings'=>$breedings,'animalbreeding'=>$animalbreeding,'male_animals'=>$male_animals,'female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians]);
    }

    public function update(Request $request,BreedingEvents $animalbreeding)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([

            'female_cow_id'=>'required|exists:animal_details,id',
            'male_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',
            'breeding_date'=>'required|string|before_or_equal:today',
            
            'insemination_type'=>'required|string',
            'notes'=>'required|string',

        ]);

        $female = AnimalDetail::find($request->female_cow_id);
        $male = AnimalDetail::find($request->male_cow_id);

        if ($request->breeding_date <= $female->animal_birthdate) 
        {
             return redirect()->back()
           ->withInput()
           ->withErrors(['the breeding date should not before the female animal birthdate']);
        }

        if ($request->breeding_date <= $male->animal_birthdate) 
        {
             return redirect()->back()
           ->withInput()
           ->withErrors(['The breeding date should not before the male_animal birthdate']);
        }

        $animalbreeding->update($data);

        return redirect()->route('animal_breedings.list')->with('success', 'Animal BreedingEvents record updated successfully!');
    }

    public function destroy(BreedingEvents $animalbreeding)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $animalbreeding->delete();

        return redirect()->route('animal_breedings.list');
    }
}
