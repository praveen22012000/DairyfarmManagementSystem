<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MilkProduct;
use App\Models\ingredient;


class MilkProductController extends Controller
{
    //

    public function index()
    {
        $milkProducts=MilkProduct::with('ingredients')->get();

        return view('milk_products.index',['milkProducts'=>$milkProducts]);
    }

    public function create()
    {
        return view('milk_products.create');
    }

    public function store(Request $request)
    {

  
  
        $request->validate([

            'product_name'=>'required|string|max:255',
            'unit_price'=>'required|numeric|min:0',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string|max:255',

        ]);

       
        


         // Create the milk product
         $milkProduct = MilkProduct::create([
            'product_name' => $request->product_name,
            'unit_price' => $request->unit_price,
        ]);

       
      

        // Store ingredients
        foreach ($request->ingredients as $ingredient) {

      

            ingredient::create([
                'product_id' => $milkProduct->id,
                'ingredients' => $ingredient,
            ]);
        }

  
        return redirect()->route('milk_product.list')->with('success', 'Milking record added successfully!');


    }

    public function edit(Request $request,MilkProduct $milkProduct)
    {
        
        return view('milk_products.edit',['milkProduct'=>$milkProduct]);

    }

    public function update(Request $request,MilkProduct $milkProduct)
    {
        $request->validate([

            'product_name'=>'required|string|max:255',
            'unit_price'=>'required|numeric|min:0',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string|max:255',

        ]);

       
        


         // Create the milk product
         $milkProduct = MilkProduct::create([
            'product_name' => $request->product_name,
            'unit_price' => $request->unit_price,
        ]);

       
      

        // Store ingredients
        foreach ($request->ingredients as $ingredient) {

      

            ingredient::create([
                'product_id' => $milkProduct->id,
                'ingredients' => $ingredient,
            ]);
        }

  
        return redirect()->route('milk_product.list')->with('success', 'Milking updated added successfully!');

    }

    public function view(MilkProduct $milkProduct)
    {
        return view('milk_products.view',['milkProduct'=>$milkProduct]);
    }

    public function destroy(MilkProduct $milkProduct)
    {
        $milkProduct->delete();
        return redirect()->route('milk_product.list');
    }
}
