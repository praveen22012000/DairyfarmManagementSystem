<?php

namespace App\Http\Controllers;


use Illuminate\Validation\Rule;

use App\Models\AnimalDetail;

use App\Models\AnimalType;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    //

    public function index()
    {
        //this methos is used to list the Animal List

        //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
        $animals=AnimalDetail::with('AnimalType')->get();

        
        return view('animal.index',['animals'=>$animals]);
    }

    public function create()//this method is used to display animal registration form 
    {
        


        $animal_types=AnimalType::all();
        //get the all animaltypes records from the database.and stored it in the $animal_types variable


        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode


        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode

        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)->get();//newcode

        
        
        return view('animal.create',['animal_types'=>$animal_types,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
        //this method  pass the breeds to the animal.create view

    }

    public function store(Request $request)
    {

        $request->validate([
           
            'animal_type_id'=>'required',
            'animal_birthdate'=>'required',
            'animal_name'=>'required|string|max:255|unique:animal_details,animal_name',
            'ear_tag'=>'required|unique:animal_details,ear_tag',
            'sire_id'=>'nullable|exists:animal_details,id',
            'dam_id'=>'nullable|exists:animal_details,id',
            'color'=>'required',
            'weight_at_birth'=>'required',
            'age_at_first_service'=>'required',
            'weight_at_first_service'=>'required'

        ]);

     
        AnimalDetail::create(
            [
                
                'animal_type_id'=>$request->animal_type_id,
                'animal_birthdate'=>$request->animal_birthdate,
                'animal_name'=>$request->animal_name,
                'ear_tag'=>$request->ear_tag,
                'sire_id'=>$request->sire_id,
                'dam_id'=>$request->dam_id,
                'color'=>$request->color,
                'weight_at_birth'=>$request->weight_at_birth,
                'age_at_first_service'=>$request->age_at_first_service,
                'weight_at_first_service'=>$request->weight_at_first_service
            ]);


            return redirect()->route('animal.list')->with('success', 'Animal record added successfully!');


    }

    public function edit(AnimalDetail $animaldetail)
    {
      
         //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
         $animals=AnimalDetail::with('AnimalType')->get();


         $animal_types=AnimalType::all();
         //get the all animaltypes records from the database.and stored it in the $animal_types variable

    
        
         $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

         $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->where('id', '!=', $animaldetail->id)->get();//newcode
 
        
 
         $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode
 
         $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)->where('id', '!=', $animaldetail->id)->get();//newcode


        return view('animal.edit',['animal_types'=>$animal_types,'animaldetail'=>$animaldetail,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
    }


    public function view(AnimalDetail $animaldetail)
    {
      
         //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
         $animals=AnimalDetail::with('AnimalType')->get();


         $animal_types=AnimalType::all();
         //get the all animaltypes records from the database.and stored it in the $animal_types variable

        

        
         $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

         $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->where('id', '!=', $animaldetail->id)->get();//newcode
 
        
 
         $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode
 
         $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)->where('id', '!=', $animaldetail->id)->get();//newcode


        return view('animal.view',['animal_types'=>$animal_types,'animaldetail'=>$animaldetail,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
    }

    

    public function update(Request $request,AnimalDetail $animaldetail)
    {

        $data=$request->validate([
            'animal_type_id'=>'required',
            'animal_birthdate'=>'required',
            'animal_name'=>"required|string|max:255|unique:animal_details,animal_name,$animaldetail->id",

            'ear_tag'=>"required|string|max:255|unique:animal_details,animal_name,$animaldetail->id",

            
            'sire_id'=>'nullable|exists:animal_details,id',
            'dam_id'=>'nullable|exists:animal_details,id',
           
            'color'=>'required',
            'weight_at_birth'=>'required',
            'age_at_first_service'=>'required',
            'weight_at_first_service'=>'required'

        ]);

        $data['sire_id'] = $data['sire_id'] ?? null;
       $data['dam_id'] = $data['dam_id'] ?? null;

        $animaldetail->update($data);

        return redirect()->route('animal.list')->with('success', 'Animal record updated successfully!');

    }

  

    public function destroy(AnimalDetail $animaldetail)
    {
        $animaldetail->delete();
        return redirect()->route('animal.list');
    }
}
