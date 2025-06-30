<?php

namespace App\Http\Controllers;

use App\Models\AnimalType;
use App\Models\AnimalDetail;
use App\Models\ProductionMilk;
use App\Models\User;
use App\Models\Role;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProductionMilkController extends Controller
{

    //dont's use this is old code
    public function monthlyReport(Request $request)
    {
    $year = $request->input('year', now()->year);

    // Fetch data grouped by month
    $productions = ProductionMilk::whereYear('production_date', $year)->get();

    $monthlyData = array_fill_keys([
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ], 0);

    foreach ($productions as $record) {
        $month = Carbon::parse($record->production_date)->format('F');
        $monthlyData[$month] += $record->Quantity_Liters;
    }

    return view('milk_production.monthly_report', compact('monthlyData', 'year'));
   }

   //dont' use this is old code
   public function animalYearlyChart(Request $request)
   {
    $year = $request->input('year');
    $animal_id = $request->input('animal_id');

    
 

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        // Fetch all female animals for the dropdown
        $animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode


    $monthlyProduction = [];

    if ($year && $animal_id) {
        $productions = ProductionMilk::whereYear('production_date', $year)
            ->where('animal_id', $animal_id)
            ->get();

        $monthlyProduction = array_fill_keys([
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ], 0);

        foreach ($productions as $record) {
            $month = Carbon::parse($record->production_date)->format('F');
            $monthlyProduction[$month] += $record->Quantity_Liters;
        }
    }

    
    return view('milk_production.animal_year_chart', compact('animals', 'monthlyProduction', 'year', 'animal_id'));
    }

    //this is new code,display the amount of milk gain from each animals between specific date
    public function generateReport(Request $request)
    {
       
    $start = $request->start_date;
    $end = $request->end_date;

   
    $milkData = [];

    if ($start && $end) 
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

     

        $milkData = \App\Models\ProductionMilk::whereBetween('production_date', [$start, $end])
            ->select('animal_id', \DB::raw('SUM(Quantity_Liters) as total_milk'))
            ->groupBy('animal_id')
            ->with('AnimalDetail')
            ->get();
    }

   

    return view('reports.milk_production_report', compact('milkData', 'start', 'end'));
    }

    //this function is used to get the milk records of the animal between the particular dates
    public function  generateReportPerAnimal(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $animalID = $request->animal_id;

        $milkData = [];

          $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');

         
          $female_animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();

      


        if ($start && $end) 
        {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'animal_id' => 'required|exists:animal_details,id'
        ]);


         $milkData = \App\Models\ProductionMilk::whereBetween('production_date', [$start, $end])
    ->where('animal_id', $animalID)
    ->with('AnimalDetail')
    ->get();

        }

        $total_quantity =0 ;

        foreach($milkData as $data)
        {
            $total_quantity=$total_quantity+$data->Quantity_Liters;
        }

          return view('reports.milk_production_report_for_animal', compact('milkData', 'start', 'end','female_animals','total_quantity'));
   
    }



    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $production_milk_details=ProductionMilk::with('AnimalDetail')->get();

        return view('milk_production.index',['production_milk_details'=>$production_milk_details]);
    }
     
    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();



        return view('milk_production.create',['female_Animals'=>$female_Animals,'farm_labors'=>$farm_labors]);

    }



    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
       
        $request->validate([

            'animal_id'=>'required|exists:animal_details,id',
            'user_id'=>'required|exists:users,id',
            'production_date'=>'required',
            'Quantity_Liters'=>'required|numeric|min:0',
            'shift'=>'required',
            'fat_percentage'=>'required',
             'protein_percentage'=>'required',
            'lactose_percentage'=>'required',
            'somatic_cell_count'=>'required',

           
           
        ]);

        $milkProduction = new ProductionMilk();
        $milkProduction->animal_id = $request->animal_id;
        $milkProduction->user_id =  $request->user_id;
        $milkProduction->production_date = $request->production_date;
        $milkProduction->Quantity_Liters = $request->Quantity_Liters;
        $milkProduction->initial_quantity_liters = $request->Quantity_Liters;
        $milkProduction->stock_quantity = $request->Quantity_Liters;
        $milkProduction->shift = $request->shift;
        $milkProduction->fat_percentage = $request->fat_percentage;
        $milkProduction->protein_percentage = $request->protein_percentage;
        $milkProduction->lactose_percentage = $request->lactose_percentage;
        $milkProduction->somatic_cell_count = $request->somatic_cell_count;

        $milkProduction->save();

        return redirect()->route('production_milk.list')->with('success', 'Milk production recorded successfully!');

        
    /*    ProductionMilk::create([

            'animal_id'=>$request->animal_id,
            'user_id'=>$request->user_id,
            'production_date'=>$request->production_date,
            'Quantity_Liters'=>$request->Quantity_Liters,
            'shift'=>$request->shift,
            'fat_percentage'=>$request->fat_percentage,
            'lactose_percentage'=>$request->lactose_percentage,
            'somatic_cell_count'=>$request->somatic_cell_count,
            'protein_percentage'=>$request->protein_percentage,

            'stock_quantity'=>$request->stock_quantity
        ]);

        return redirect()->route('production_milk.list')->with('success', 'Milking record added successfully!');*/
    }


    public function view(ProductionMilk $productionmilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();


    
        return view('milk_production.view',['female_Animals'=>$female_Animals,'productionmilk'=>$productionmilk,'farm_labors'=> $farm_labors]);
    }

    public function edit(ProductionMilk $productionmilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();

    
        return view('milk_production.edit',['female_Animals'=>$female_Animals,'productionmilk'=>$productionmilk,'farm_labors'=>$farm_labors]);

    }

    public function update(Request $request,ProductionMilk $productionmilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $data=$request->validate([

            
            'animal_id'=>'required|exists:animal_details,id',
            'user_id'=>'required|exists:users,id',
            'production_date'=>'required',
            'Quantity_Liters'=>'required',
            'shift'=>'required',
            'fat_percentage'=>'required',
             'protein_percentage'=>'required',
            'lactose_percentage'=>'required',
            'somatic_cell_count'=>'required',


        ]);

         // Update all fields
            $productionmilk->animal_id = $data['animal_id'];
            $productionmilk->user_id = $data['user_id'];
            $productionmilk->production_date = $data['production_date'];
            $productionmilk->shift = $data['shift'];
            $productionmilk->fat_percentage = $data['fat_percentage'];
            $productionmilk->protein_percentage = $data['protein_percentage'];
            $productionmilk->lactose_percentage = $data['lactose_percentage'];
            $productionmilk->somatic_cell_count = $data['somatic_cell_count'];


        // Calculate already consumed milk
        $consumedMilk = $productionmilk->initial_quantity_liters - $productionmilk->stock_quantity;

        // Update Quantity_Liters and initial_quantity_liters
        $productionmilk->Quantity_Liters = $request->Quantity_Liters;
        $productionmilk->initial_quantity_liters = $request->Quantity_Liters;

        // Update stock quantity without affecting already consumed milk
        $productionmilk->stock_quantity = max($request->Quantity_Liters - $consumedMilk, 0);

        $productionmilk->save();

        return redirect()->route('production_milk.list')->with('success', 'Milk production updated successfully!');

    //    $productionmilk->update($data);


    //    return redirect()->route('production_milk.list')->with('success', 'Milk Production record updated successfully!');
    }
    
    public function destroy(ProductionMilk $productionmilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $productionmilk->delete();

        return redirect()->route('production_milk.list');
    }
}
