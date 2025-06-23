<?php

namespace App\Http\Controllers;

use App\Models\AnimalDetail;
use App\Models\AnimalType;
use App\Models\User;
use App\Models\Role;
use App\Models\BreedingEvents;
use App\Models\Pregnancies;

use Illuminate\Http\Request;

class PregnanciesController extends Controller
{
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $pregnancies=Pregnancies::with(['AnimalDetail','user'])->get();

        return view('animal_pregnancies.index',['pregnancies'=>$pregnancies]);
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

        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

       $breedings=BreedingEvents::with('femalecow')->get();

        return view('animal_pregnancies.create',['female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians,'breedings'=>$breedings]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'breeding_id'=>'required|exists:breeding_events,id|unique:pregnancies,breeding_id',
            'female_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',

            'pregnancy_status'=>'required|string',
            'estimated_calving_date'=>'required',
            'confirmation_date'=>'required'
        ]);

        Pregnancies::create([

            'breeding_id'=>$request->breeding_id,
            'female_cow_id'=>$request->female_cow_id,
            'veterinarian_id'=>$request->veterinarian_id,
            
            'pregnancy_status'=>$request->pregnancy_status,
            'estimated_calving_date'=>$request->estimated_calving_date,
            'confirmation_date'=>$request->confirmation_date


        ]);

        return view('animal_pregnancies.index');
    }

    public function edit(Pregnancies $pregnancie)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
        ->where('status','alive')
        ->get();

        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

       $breedings=BreedingEvents::with('femalecow')->get();

       $animalpregnancies=Pregnancies::all();

       return view('animal_pregnancies.edit',['female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians,'breedings'=>$breedings,'animalpregnancies'=>$animalpregnancies,'pregnancie'=>$pregnancie]);

    }

    public function update(Pregnancies $pregnancie,Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([

            'breeding_id'=>"required|exists:breeding_events,id|unique:pregnancies,breeding_id,$pregnancie->id",
            'female_cow_id'=>'required|exists:animal_details,id',
            'veterinarian_id'=>'required|exists:users,id',

            'pregnancy_status'=>'required|string',
            'estimated_calving_date'=>'required',
            'confirmation_date'=>'required'

        ]);

        $pregnancie->update($data);

        return redirect()->route('animal_pregnancies.list')->with('success', 'AnimalPregnancy record updated successfully!');
    }

    public function view(Pregnancies $pregnancie)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
        ->where('status','alive')
        ->get();

        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

       $breedings=BreedingEvents::with('femalecow')->get();

       $animalpregnancies=Pregnancies::all();

       return view('animal_pregnancies.view',['female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians,'breedings'=>$breedings,'animalpregnancies'=>$animalpregnancies,'pregnancie'=>$pregnancie]);

    }

    public function destroy(Pregnancies $pregnancie)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $pregnancie->delete();

        return redirect()->route('animal_pregnancies.list');
    }
}
