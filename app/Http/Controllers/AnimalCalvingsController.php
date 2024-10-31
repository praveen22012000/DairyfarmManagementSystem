<?php

namespace App\Http\Controllers;


use App\Models\AnimalType;
use App\Models\AnimalCalvings;
use App\Models\AnimalDetail;
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
        //this is used to get the type of the animals cow,and heifer from the animal_type table in db.pluck() method return only the animal_type_id of the animals cow and heifer
        //$animal_types_id contain the id's of the Animal Cow and Heifer
        $animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

        
        //$Parent_female_Animals variable contain the details of the animals which are Cow and heifer
        $Parent_female_Animals = AnimalDetail::whereIn('animal_type_id', $animal_types_id)->get();
     
      
        //this is used to get the type of the animals Heifer,and BullCalf from the animal_type table in db.pluck() method return only the animal_type_id of the animals Heifer and BullCalf
        $calf_animal_types_id= AnimalType::whereIn('animal_type',['Heifer','BullCalf'])->pluck('id');

        //$Child_animals variable contain the details of the animal which are heifer and BullCalf
        $Child_animals=AnimalDetail::whereIn('animal_type_id',$calf_animal_types_id)->get();

       

        return view('animal_calving.create',['Parent_female_Animals'=>$Parent_female_Animals,'Child_animals'=>$Child_animals]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'calf_id'=>'required',
            'parent_cow_id'=>'required',
            'calving_date'=>'required',
            'calving_notes'=>'required'

        ]);

        AnimalCalvings::create([
            'calf_id'=>$request->calf_id,
            'parent_cow_id'=>$request->parent_cow_id,
            'calving_date'=>$request->calving_date,
            'calving_notes'=>$request->calving_notes
        ]);

        return redirect()->route('animal_calvings.list');
    }

    public function edit()
    {

    }
    
}
