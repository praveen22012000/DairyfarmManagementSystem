<?php

namespace App\Http\Controllers;

use App\Models\AnimalDetail;
use App\Models\AnimalType;
use App\Models\User;
use App\Models\Role;
use App\Models\BreedingEvents;
use App\Models\Pregnancies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PregnanciesController extends Controller
{
    //

    public function getBreedingEvents($female_cow_id)
    {
    $breedings = BreedingEvents::where('female_cow_id', $female_cow_id)
        ->whereDoesntHave('pregnancy')// Ensure this breeding event has no associated pregnancy
        ->with(['femalecow', 'malecow']) // Optional: for names
        ->get();

    return response()->json($breedings);
    }

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

        $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

       $veterinarians=User::whereIn('role_id',$veterinarian_id)->get();

       $used_pregnancy_ids = Pregnancies::pluck('breeding_id');
        
     // $breedings=BreedingEvents::with('femalecow')->get();

     // dd($breedings);

       $breedings=BreedingEvents::whereNotIn('id',$used_pregnancy_ids)->with('femalecow')->get();
      
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

        
        // Fetch the breeding event record
        $breedingEvent = BreedingEvents::find($request->breeding_id);

        $breeding_date = Carbon::parse($breedingEvent->breeding_date);

        $estimated_Calving_date = Carbon::parse($request->estimated_calving_date);

        

        if ($breedingEvent->breeding_date >= $request->confirmation_date) 
        {
            return back()->withInput()->withErrors([
                'confirmation_date' => 'Confirmation date must be after the breeding date (' . $breedingEvent->breeding_date . ').',
            ]);

        }

        if($request->confirmation_date >= $request->estimated_calving_date)
        {
            return back()->withInput()->withErrors
            ([
                'estimated_calving_date' => 'Estimated calving date must be after the confirmation date (' . $request->confirmation_date . ').',
            ]);
        }

        if ($estimated_Calving_date < $breeding_date->copy()->addMonths(8)) 
        {
            return back()->withInput()->withErrors
            ([
                'estimated_calving_date' => 'The estimated calving date must be at least 8 months after the breeding date (' . $breeding_date->toDateString() . '), which is on ' . $breeding_date->copy()->addMonths(8)->toDateString() . '.',
            ]);
        }
        
        Pregnancies::create([

            'breeding_id'=>$request->breeding_id,
            'female_cow_id'=>$request->female_cow_id,
            'veterinarian_id'=>$request->veterinarian_id,
            
            'pregnancy_status'=>$request->pregnancy_status,
            'estimated_calving_date'=>$request->estimated_calving_date,
            'confirmation_date'=>$request->confirmation_date


        ]);

       return redirect()->route('animal_pregnancies.list')->with('success', 'AnimalPregnancy record stored successfully!');
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

        $veterinarian_id=Role::whereIn('role_name',['Veterinarion'])->pluck('id');

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

           // Fetch the breeding event record
        $breedingEvent = BreedingEvents::find($request->breeding_id);

        if ($breedingEvent->breeding_date >= $request->confirmation_date) 
        {
            return back()->withInput()->withErrors([
                'confirmation_date' => 'Confirmation date must be after the breeding date (' . $breedingEvent->breeding_date . ').',
            ]);

        }

        if($request->confirmation_date >= $request->estimated_calving_date)
        {
            return back()->withInput()->withErrors([
                'estimated_calving_date' => 'Estimated calving date must be after the confirmation date (' . $request->confirmation_date . ').',
            ]);
        }



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
