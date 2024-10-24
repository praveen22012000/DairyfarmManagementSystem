<?php

namespace App\Http\Controllers;

use App\Models\AnimalDetail;
use App\Models\Breed;
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
        $breeds=Breed::all();
        //get the all breed records from the database.and stored it in the $breeds variable


        $animal_types=AnimalType::all();
        //get the all animaltypes records from the database.and stored it in the $animal_types variable

        return view('animal.create',['breeds'=>$breeds,'animal_types'=>$animal_types]);
        //this method  pass the breeds to the animal.create view

    }

    public function store(Request $request)
    {
        $request->validate([
           
            'animal_type_id'=>'required',
            'animal_birthdate'=>'required',
            'animal_name'=>'required|string|max:255',
            'ear_tag'=>'required',
            'sire_id'=>'required',
            'dam_id'=>'required',
            'breed_id'=>'required',
            'color'=>'required',
            'weight_at_birth'=>'required',
            'age_at_birth'=>'required',
            'weight_at_first_service'=>'required'

        ]);

     /*   $imagePath = null;
        if ($request->hasFile('animal_image')) {
            $imagePath = $request->file('animal_image')->store('animal_images', 'public');
        }*/

        AnimalDetail::create(
            [
                
                'animal_type_id'=>$request->animal_type_id,
                'animal_birthdate'=>$request->animal_birthdate,
                'animal_name'=>$request->animal_name,
                'ear_tag'=>$request->ear_tag,
                'sire_id'=>$request->sire_id,
                'dam_id'=>$request->dam_id,
                'breed_id'=>$request->breed_id,
                'color'=>$request->color,
                'weight_at_birth'=>$request->weight_at_birth,
                'age_at_birth'=>$request->age_at_birth,
                'weight_at_first_service'=>$request->weight_at_first_service
            ]);


            return redirect()->route('animal.list');


    }

    public function edit(AnimalDetail $animaldetail)
    {
      
         //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
         $animals=AnimalDetail::with('AnimalType')->get();


         $animal_types=AnimalType::all();
         //get the all animaltypes records from the database.and stored it in the $animal_types variable

         $breeds=Breed::all();
         //get the all breed records from the database.and stored it in the $breeds variable

        return view('animal.edit',['animal_types'=>$animal_types,'animaldetail'=>$animaldetail,'breeds'=>$breeds]);
    }


    public function update(Request $request,AnimalDetail $animaldetail)
    {

        $data=$request->validate([
            'animal_type_id'=>'required',
            'animal_birthdate'=>'required',
            'animal_name'=>'required|string|max:255',
            'ear_tag'=>'required',
            'sire_id'=>'required',
            'dam_id'=>'required',
            'breed_id'=>'required',
            'color'=>'required',
            'weight_at_birth'=>'required',
            'age_at_birth'=>'required',
            'weight_at_first_service'=>'required'

        ]);

        $animaldetail->update($data);

        return redirect()->route('animal.list');

    }

    public function delete(AnimalDetail $animaldetail)
    {
        return view('animal.delete',['animaldetail'=>$animaldetail]);
    }

    public function destroy(AnimalDetail $animaldetail)
    {
        $animaldetail->delete();
        return redirect()->route('animal.list');
    }
}
