<?php

namespace App\Http\Controllers;

use App\Models\AnimalType;
use App\Models\AnimalDetail;
use App\Models\ProductionMilk;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class ProductionMilkController extends Controller
{
    //
    public function index()
    {
        $production_milk_details=ProductionMilk::with('AnimalDetail')->get();

       

        return view('milk_production.index',['production_milk_details'=>$production_milk_details]);
    }
     
    public function create()
    {
       
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();



        return view('milk_production.create',['female_Animals'=>$female_Animals,'farm_labors'=>$farm_labors]);

    }



    public function store(Request $request)
    {

       
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
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();


    
        return view('milk_production.view',['female_Animals'=>$female_Animals,'productionmilk'=>$productionmilk,'farm_labors'=> $farm_labors]);
    }

    public function edit(ProductionMilk $productionmilk)
    {
        $female_animal_types_id = AnimalType::whereIn('animal_type', ['Cow', 'Heifer'])->pluck('id');//newcode

        $female_Animals = AnimalDetail::whereIn('animal_type_id', $female_animal_types_id)->get();//newcode

        $farmlabors_id=Role::whereIn('role_name',['FarmLabore'])->pluck('id');

        $farm_labors=User::whereIn('role_id',$farmlabors_id)->get();

    
        return view('milk_production.edit',['female_Animals'=>$female_Animals,'productionmilk'=>$productionmilk,'farm_labors'=>$farm_labors]);

    }

    public function update(Request $request,ProductionMilk $productionmilk)
    {
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
        $productionmilk->delete();

        return redirect()->route('production_milk.list');
    }
}
