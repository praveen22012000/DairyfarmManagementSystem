<?php

namespace App\Http\Controllers;


use Illuminate\Validation\Rule;

use App\Models\AnimalDetail;

use App\Models\AnimalType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AnimalController extends Controller
{
    //
// this is the new code for find the animals who born between the specific dates
    public function generateAnimalReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $AnimalData= [];

        if($start && $end)
        {
             $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $AnimalData= AnimalDetail::whereBetween('animal_birthdate',[$start, $end])
            ->get();

           
        }

         $totalAnimals=0;

            foreach($AnimalData as $animal)
            {
                $totalAnimals=$totalAnimals+1;
            }
        return view('reports.animal_birth_report',compact('start','end','AnimalData','totalAnimals'));
    }

    public function generateAnimalReportPDF(Request $request)
    {
         $start = $request->start_date;
        $end = $request->end_date;

        $AnimalData= [];

        if($start && $end)
        {
             $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $AnimalData= AnimalDetail::whereBetween('animal_birthdate',[$start, $end])
            ->get();

           
        }

         $totalAnimals=0;

            foreach($AnimalData as $animal)
            {
                $totalAnimals=$totalAnimals+1;
            }
        
            
         $pdfInstance = Pdf::loadView('reports_pdf.animal_birth_report_pdf', compact('start','end','AnimalData','totalAnimals'));
         return $pdfInstance->download('Animal Birth Report.pdf');
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $q=$request->input('q');

        if($q)
        {
            $animals = AnimalDetail::where('animal_name','like',"%{$q}%")//it checks the animals names that contain the text $q
            ->orWhere('animal_type_id','like',"%{$q}%")//This checks if the animal_type_id contains the search term $q
            ->with('AnimalType')
            ->orderBy('id', 'desc')//This line sorts the results based on the id field in descending order.
            ->paginate();
        }
        else
        {
            $animals = AnimalDetail::with('AnimalType')//If there's no search input, just get the list of all animals
            ->orderBy('id', 'desc')
            ->paginate(5);//Paginates the list with 5 animals per page

        }

        $animal_types=AnimalType::all();

           // Return the view with the paginated results
           return view('animal.index', [
            'animals' => $animals,
            'animal_types'=>$animal_types
        ]);
      
    }


    public function filterByType(Request $request)// This defines a controller method named filterByType
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $animalTypeId = $request->input('animal_type_id');//This line gets the selected animal type ID from the request sent by the JavaScript.

        $animals = AnimalDetail::where('animal_type_id', $animalTypeId)//Fetches all animals from the animal_details table where the animal_type_id matches the selected value.
        ->with('AnimalType') // Load the related animal type
        ->get() // Executes the query and returns the collection of matching animals.
        ->map(function ($animal) {
            return [
                'id' => $animal->id,
                'animal_name' => $animal->animal_name,
                'animal_type' => $animal->AnimalType->animal_type ?? 'Unknown', // Access relationship safely
                'age' => $animal->age, // Make sure age is computed or available
            ];
        });

        //map() is a function in Laravel's collections that takes every item in a collection, 
        // applies a transformation (a change), and returns a new collection with the transformed items.



        //below is the purpose of the json
        //Convert PHP data (like arrays, objects, or collections) into a JSON (JavaScript Object Notation) format.
        //Send this JSON-formatted data as an HTTP response to the client (usually the frontend or an API consumer).
        return response()->json($animals);
    }  
    

    public function create()//this method is used to display animal registration form 
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }



        $animal_types=AnimalType::all();
        //get the all animaltypes records from the database.and stored it in the $animal_types variable


        //this line gets id of the female animals
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        //this line get the female animals which they are currently alive
        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
                                  ->where('status', 'alive') // Filter only alive animals
                                  ->get();

        //this line gets the id of the male animals
        $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode

        //this line gets the animal which they are currently alive
        $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
                                    ->where('status','alive')
                                    ->get();//newcode

        
        
        return view('animal.create',['animal_types'=>$animal_types,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
        //this method  pass the breeds to the animal.create view

    }

    public function store(Request $request)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }


       $request->validate([
            'animal_type_id' => 'required',
            'animal_birthdate' => 'required|before_or_equal:today',
            'animal_name' => 'required|string|max:255|unique:animal_details,animal_name',
            'ear_tag' => 'required|unique:animal_details,ear_tag',
            'sire_id' => 'nullable|exists:animal_details,id',
            'dam_id' => 'nullable|exists:animal_details,id',

            'status' => 'required|in:alive,deceased',
            'death_date' => [
                                'nullable',
                                'date',
                                'required_if:status,deceased',
                                 function ($attribute, $value, $fail) use ($request) 
                                 {
                                    if ($value && $request->animal_birthdate && $value < $request->animal_birthdate) 
                                    {
                                    $fail('The death date should not be before the birthdate.');
                                    }
                                }
        ],

            'color' => 'required',
            'weight_at_birth' => 'required|numeric|min:1',
            'age_at_first_service' => 'required|numeric|min:0',
            'weight_at_first_service' => 'required|numeric|min:0',
]);

     
     
        AnimalDetail::create(
            [
                
                'animal_type_id'=>$request->animal_type_id,
                'animal_birthdate'=>$request->animal_birthdate,
                'animal_name'=>$request->animal_name,
                'ear_tag'=>$request->ear_tag,
                'sire_id'=>$request->sire_id,
                'dam_id'=>$request->dam_id,

                'status' => $request->status,
                'death_date' => $request->status === 'deceased' ? $request->death_date : null,


                'color'=>$request->color,
                'weight_at_birth'=>$request->weight_at_birth,
                'age_at_first_service'=>$request->age_at_first_service,
                'weight_at_first_service'=>$request->weight_at_first_service
            ]);


            return redirect()->route('animal.list')->with('success', 'Animal record added successfully!');


    }

    public function edit(AnimalDetail $animaldetail)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

      
         //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
         $animals=AnimalDetail::with('AnimalType')->get();


         $animal_types=AnimalType::all();
         //get the all animaltypes records from the database.and stored it in the $animal_types variable

    
        
         $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

         $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
         ->where('status', 'alive') // Filter only alive animals
         ->get();
 
        
 
         $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode
 
         $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
         ->where('status','alive')
         ->get();//newcode

        return view('animal.edit',['animal_types'=>$animal_types,'animaldetail'=>$animaldetail,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
    }


    public function view(AnimalDetail $animaldetail)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

      
         //this line get the AnimalType details along with AnimalDetail using the AnimalType relationship(define on the AnimalDetailModel)
         $animals=AnimalDetail::with('AnimalType')->get();


         $animal_types=AnimalType::all();
         //get the all animaltypes records from the database.and stored it in the $animal_types variable

        

        
         $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

         $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)
         ->where('status', 'alive') // Filter only alive animals
         ->get();
        
 
         $male_animal_types_id= AnimalType::whereIn('animal_type',['Bull','BullCalf'])->pluck('id');//newcode
 
         $male_animals=AnimalDetail::whereIn('animal_type_id',$male_animal_types_id)
         ->where('status','alive')
         ->get();//newcode



        return view('animal.view',['animal_types'=>$animal_types,'animaldetail'=>$animaldetail,'female_Animals'=>$female_Animals,'male_animals'=>$male_animals]);
    }

    

    public function update(Request $request,AnimalDetail $animaldetail)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $data=$request->validate([
            'animal_type_id'=>'required',
            'animal_birthdate'=>'required|before_or_equal:today',
            'animal_name'=>"required|string|max:255|unique:animal_details,animal_name,$animaldetail->id",

            'ear_tag'=>"required|string|max:255|unique:animal_details,animal_name,$animaldetail->id",

            
            'sire_id'=>'nullable|exists:animal_details,id',
            'dam_id'=>'nullable|exists:animal_details,id',

            'status' => 'required|in:alive,deceased',
              'death_date' => [
                                'nullable',
                                'date',
                                'required_if:status,deceased',
                                 function ($attribute, $value, $fail) use ($request) 
                                 {
                                    if ($value && $request->animal_birthdate && $value < $request->animal_birthdate) 
                                    {
                                    $fail('The death date should not be before the birthdate.');
                                    }
                                }
        ],
           
            'color'=>'required',
            'weight_at_birth'=>'required|numeric|min:1',
            'age_at_first_service'=>'required|numeric|min:0',
            'weight_at_first_service'=>'required|numeric|min:0'

        ], [
            'animal_birthdate.before_or_equal' => 'Animal birthdate should be today or a past date.',
            'ear_tag.unique'=>'Ear Tag should be unique'
        ]);

        $animaldetail->update([
            'animal_type_id'=>$request->animal_type_id,
            'animal_birthdate'=>$request->animal_birthdate,
            'animal_name' => $request->animal_name,
            'ear_tag'=>$request->ear_tag,
            'sire_id'=>$request->sire_id ?? null,
            'dam_id'=>$request->dam_id ?? null,

            'status'=>$request->status,
            'death_date'=>$request->status === 'deceased' ? $request->death_date : null,

            'color'=>$request->color,
            'weight_at_birth'=>$request->weight_at_birth,
            'age_at_first_service'=>$request->age_at_first_service,
            'weight_at_first_service'=>$request->weight_at_first_service

          
        ]);

      
    

        return redirect()->route('animal.list')->with('success', 'Animal record updated successfully!');

    }

  

    public function destroy(AnimalDetail $animaldetail)
    {
         if (!in_array(Auth::user()->role_id, [1, 2, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $animaldetail->delete();
        return redirect()->route('animal.list');
    }
}
