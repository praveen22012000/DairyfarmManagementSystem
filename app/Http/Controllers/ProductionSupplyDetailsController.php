<?php

namespace App\Http\Controllers;

use App\Models\ProductionMilk;
use App\Models\MilkProduct;

use App\Models\ProductionSupply;
use App\Models\ProductionSupplyDetails;


use Illuminate\Http\Request;

class ProductionSupplyDetailsController extends Controller
{
    //

    public function index()
    {
        $productionSupply=ProductionSupply::all();

        $productionSupplyDetails=ProductionSupplyDetails::with(['production_milk','milk_product'])->get();



        return view('supply_manufacturing_milk.index',['productionSupply'=>$productionSupply,'productionSupplyDetails'=>$productionSupplyDetails]);
    }

    public function create()
    {
         // Fetch records where stock_quantity > 0
         $ProductionsMilk = ProductionMilk::where('stock_quantity', '>', 0)->get();

         $milkProducts=MilkProduct::all();

     

        return view('supply_manufacturing_milk.create',['ProductionsMilk'=>$ProductionsMilk,'milkProducts'=>$milkProducts]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'date'=>'required',
            'time'=>'required',
            'entered_by'=>'required',

            'consumed_quantity' => 'required|array',
            'consumed_quantity.*' => 'nullable|numeric|min:0',
         
            'product_id'=>'required|exists:milk_products,id',
            

        ]);

        $productionSupply=ProductionSupply::create([
            'date'=>$request->date,
            'time'=>$request->time,
            'entered_by'=>$request->entered_by

        ]);


       // Process each consumed quantity
       foreach ($request->consumed_quantity as $index => $consumed) {
        if ($consumed !== null && $consumed > 0) {
            // Find the corresponding milk production record
            $milkProductions = ProductionMilk::where('stock_quantity', '>', 0)->get();

            

         

            foreach ($milkProductions as $milkProduction) {  //  Loop through each model instance
                if ($milkProduction->stock_quantity >= $consumed) {
                    // Deduct the consumed quantity from stock quantity
                    $milkProduction->stock_quantity -= $consumed;
                    $milkProduction->save();
    
                    // Store consumption details
                    ProductionSupplyDetails::create([
                        'production_milk_id' => $milkProduction->id,
                        'production_supply_id' => $productionSupply->id,
                        'product_id'=>$request->product_id,
                        'consumed_quantity' => $consumed,
                    ]);
    
                    break; // Stop after deducting from the first available milkProduction
                }
    
        }



        }
    }

    }


    public function view(ProductionSupplyDetails $productionSupplyDetails)
    {
        

         $milkProducts=MilkProduct::all();

          // Ensure the relationship is loaded
         $productionSupplyDetails->load('production_milk', 'milk_product', 'production_supply');

      

        return view('supply_manufacturing_milk.view',['milkProducts'=>$milkProducts,'productionSupplyDetails'=>$productionSupplyDetails]);

    }

    public function edit(ProductionSupplyDetails $productionSupplyDetails)
    {
        $milkProducts=MilkProduct::all();

        // Ensure the relationship is loaded
        $productionSupplyDetails->load('production_milk', 'milk_product', 'production_supply');

    

        return view('supply_manufacturing_milk.edit',['milkProducts'=>$milkProducts,'productionSupplyDetails'=>$productionSupplyDetails]);
    }

    public function update(Request $request,ProductionSupplyDetails $productionSupplyDetails)
    {
       

  
        $data=$request->validate([

            'date'=>'required',
            'time'=>'required',
            'entered_by'=>'required',

            'consumed_quantity' => 'required|numeric|min:0',
          
            'product_id'=>'required',$productionSupplyDetails->id,
        ]);
     dd("1");

      
        $productionSupply=ProductionSupply::create([
            'date'=>$request->date,
            'time'=>$request->time,
            'entered_by'=>$request->entered_by

        ]);

         // Store consumption details
         ProductionSupplyDetails::create([
            'production_milk_id' => $productionSupplyDetails->id,
            'production_supply_id' => $productionSupplyDetails->id,
            'product_id'=>$productionSupplyDetails->product_id,
            'consumed_quantity' => $productionSupplyDetails->consumed_quantity,
        ]);

    }
}
