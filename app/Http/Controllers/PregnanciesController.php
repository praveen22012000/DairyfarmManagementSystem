<?php

namespace App\Http\Controllers;

use App\Models\AnimalDetail;
use App\Models\AnimalType;
use App\Models\User;
use App\Models\Role;
use App\Models\BreedingEvents;

use Illuminate\Http\Request;

class PregnanciesController extends Controller
{
    //


    public function create()
    {
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();

        $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

       $breedings=BreedingEvents::with('femalecow')->get();

        return view('animal_pregnancies.create',['female_Animals'=>$female_Animals,'veterinarians'=>$veterinarians,'breedings'=>$breedings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'breeding_id'=>'required|exists:breeding_events,id',
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
    }
}
