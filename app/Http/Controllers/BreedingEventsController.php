<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimalType;
use App\Models\AnimalDetail;
use App\Models\User;
use App\Models\Role;


class BreedingEventsController extends Controller
{
    //
    public function index()
    {
        return view('animal_breeding.index');

    }

    public function create()
    {


        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)->get();


       $veterinarian_id=Role::whereIn('role_name',['veterinarian'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

  




        
        return view('animal_breeding.create',['female_Animals'=>$female_Animals,'male_animals'=>$male_animals,'veterinarians'=>$veterinarians]);
    }
}
