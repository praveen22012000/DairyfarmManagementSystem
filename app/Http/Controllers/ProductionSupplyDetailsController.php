<?php

namespace App\Http\Controllers;

use App\Models\ProductionMilk;
use App\Models\MilkProduct;

use App\Models\ProductionSupply;
use App\Models\ProductionSupplyDetails;
use App\Models\ManufacturerProduct;
use Carbon\Carbon;


use Illuminate\Http\Request;

class ProductionSupplyDetailsController extends Controller
{
    //
    public function monthlyReport(Request $request)
    {
    $year = $request->input('year');

    // Get years available from production_supplies
    $years = \DB::table('production_supplies')
        ->selectRaw('YEAR(date) as year')
        ->distinct()
        ->pluck('year');

    // Monthly array default to 0
    $monthlyConsumption = array_fill_keys([
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ], 0);

    if ($year) {
        $records = ProductionSupplyDetails::with('production_supply')
            ->whereHas('production_supply', function ($query) use ($year) {
                $query->whereYear('date', $year);
            })->get();

        foreach ($records as $detail) {
            $month = Carbon::parse($detail->production_supply->date)->format('F');
            $monthlyConsumption[$month] += $detail->consumed_quantity;
        }
    }

    return view('supply_manufacturing_milk.monthly_report', compact('monthlyConsumption', 'year', 'years'));
    }

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
        'date' => 'required|date',
        'time' => 'required',
        'entered_by' => 'required|string',
        'production_milk_id' => 'required|array',
        'production_milk_id.*' => 'exists:production_milks,id',
        'consumed_quantity' => 'required|array',
        'consumed_quantity.*' => 'numeric|min:0',
        'product_id' => 'required|array',
        'product_id.*' => 'exists:milk_products,id',
    ]);

    $productionMilkIds = $request->production_milk_id;
    $consumedQuantities = $request->consumed_quantity;

    // Array to store invalid rows
    $invalidRows = [];

    $errors = [];

    // First, check if any row exceeds stock quantity
    foreach ($productionMilkIds as $index => $productionMilkId) 
    {
        $stockMilk = ProductionMilk::find($productionMilkId);

        if ($stockMilk && $consumedQuantities[$index] > $stockMilk->stock_quantity) {
          //  $invalidRows[] = $index + 1; // Store row number for error message
          $errors["consumed_quantity.$index"] = "The entered quantity ($consumedQuantities[$index]) exceeds the available stock ($stockMilk->stock_quantity).";
           
        }
    }

   
    if (!empty($errors)) 
    {
        return redirect()->back()->withErrors($errors)->withInput();
    }
    



    // If all rows are valid, proceed with deduction
    $productionSupply = ProductionSupply::create([
        'date' => $request->date,
        'time' => $request->time,
        'entered_by' => $request->entered_by,
    ]);

    foreach ($productionMilkIds as $index => $productionMilkId) {
        $stockMilk = ProductionMilk::find($productionMilkId);

        if ($stockMilk) {
            // Deduct stock quantity
            $stockMilk->stock_quantity -= $consumedQuantities[$index];
            $stockMilk->save();
        }

        // Save the milk consumption record
        ProductionSupplyDetails::create([
            'production_milk_id' => $productionMilkId,
            'production_supply_id' => $productionSupply->id,
            'product_id' => $request->product_id[$index],
            'consumed_quantity' => $consumedQuantities[$index],
        ]);
    }

    return redirect()->route('milk_allocated_for_manufacturing.index')->with('success', 'Milk consumption record saved successfully.');
}
/*

    public function store(Request $request)
    {

       
        
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'entered_by' => 'required|string',
            'production_milk_id' => 'required|array',
            'production_milk_id.*' => 'exists:production_milks,id',
            'consumed_quantity' => 'required|array',
            'consumed_quantity.*' => 'numeric|min:0',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:milk_products,id',
        ]);
     

        
        $productionMilkIds = $request->production_milk_id;
        $consumedQuantities = $request->consumed_quantity;

        $errors = [];

         // Check if any row exceeds stock quantity
        foreach ($productionMilkIds as $index => $productionMilkId) 
        {
                $stockMilk = ProductionMilk::find($productionMilkId);
        
                if ($stockMilk && $consumedQuantities[$index] > $stockMilk->stock_quantity) 
                {
                return redirect()->back()->withErrors(['error' => 'One or more rows contain a consumed quantity greater than the available stock. No data has been deducted.'])->withInput();
                }
        }


        // If all rows are valid, proceed with deduction
    foreach ($productionMilkIds as $index => $productionMilkId) {
        $stockMilk = ProductionMilk::find($productionMilkId);
        
        if ($stockMilk) {
            $stockMilk->stock_quantity -= $consumedQuantities[$index];
            $stockMilk->save();
        }

        // Save the milk consumption record
        $productionSupply = ProductionSupply::create([
            'date' => $request->date,
            'time' => $request->time,
            'entered_by' => $request->entered_by,
        ]);

        ProductionSupplyDetails::create([
            'production_milk_id' =>  $productionMilkId,
            'production_supply_id' => $productionSupply->id,
            'product_id' => $request->product_id[$index],
            'consumed_quantity' => $consumedQuantities[$index],
        ]);
    }

    return redirect()->route('milk_allocated_for_manufacturing.index')->with('success', 'Milk consumption record saved successfully.');

    }*/


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

        $ProductionsMilk = ProductionMilk::where('stock_quantity', '>', 0)->get();

        // Ensure the relationship is loaded
        $productionSupplyDetails->load('production_milk', 'milk_product', 'production_supply');

        
        return view('supply_manufacturing_milk.edit',['milkProducts'=>$milkProducts,'productionSupplyDetails'=>$productionSupplyDetails,'ProductionsMilk'=>$ProductionsMilk]);
    }

    public function update(ProductionSupplyDetails $productionSupplyDetails,Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'entered_by' => 'required|string',
            'production_milk_id' => 'required|exists:production_milks,id',
            'consumed_quantity' => 'required|numeric|min:0',
            'product_id' => 'required|exists:milk_products,id',
        ]);

        $errors = [];



      
        if ($request->consumed_quantity > $productionSupplyDetails->production_milk->stock_quantity) 
            {

             
            //  $invalidRows[] = $index + 1; // Store row number for error message
            $errors["consumed_quantity"] = "The entered quantity  exceeds the available stock quantity .";
             
            }

          if (!empty($errors)) 
          {
            return redirect()->back()->withErrors($errors)->withInput();
          }




          $productionSupplyDetails->production_supply->update([
            'date' => $request->date,
            'time' => $request->time,
            'entered_by' => $request->entered_by,
        ]);

        $new_consumed_quantity =$productionSupplyDetails->production_milk->stock_quantity+$productionSupplyDetails->consumed_quantity-$request->consumed_quantity;

        
        $productionSupplyDetails->update([
            'production_milk_id' => $request->production_milk_id,
            'production_supply_id' => $productionSupplyDetails->production_supply_id,
            'product_id' => $request->product_id,
            'consumed_quantity' => $request->consumed_quantity,
        ]);

        $productionSupplyDetails->production_milk->update([
            'stock_quantity'=>$new_consumed_quantity
        ]);

        return redirect()->route('milk_allocated_for_manufacturing.index')->with('success', 'Milk consumption record saved successfully.');
    }

    public function destroy(ProductionSupplyDetails $productionSupplyDetails)
    {
        $productionSupplyDetails->delete();

        return redirect()->route('milk_allocated_for_manufacturing.index');
    }


    
}


