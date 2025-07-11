<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MilkProduct;
use App\Models\ingredient;
use Illuminate\Support\Facades\Auth;

class MilkProductController extends Controller
{
    //

    public function index()
    {
         if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $milkProducts=MilkProduct::with('ingredients')->get();

        return view('milk_products.index',['milkProducts'=>$milkProducts]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        return view('milk_products.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
  
  
        $request->validate([

            'product_name'=>'required|string|max:255|unique:milk_products,product_name',
            'unit_price'=>'required|numeric|min:1',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string|max:255',

        ]);

        $ingredients = $request->ingredients;
        
        if (count($ingredients) !== count(array_unique($ingredients))) 
        {
                return back()
                    ->withErrors(['ingredients' => 'Duplicate ingredients are not allowed.'])
                    ->withInput();
        }


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
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        return view('milk_products.edit',['milkProduct'=>$milkProduct]);

    }

    public function update(Request $request, MilkProduct $milkProduct)
    {

        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

    $data = $request->validate([
        'product_name' => 'required|string|max:255',
        'unit_price' => 'required|numeric|min:1',
        'ingredients' => 'required|array',
        'ingredients.*' => 'required|string|max:255',
    ]);

    // **Update the existing MilkProduct**
    $milkProduct->update([
        'product_name' => $request->product_name,
        'unit_price' => $request->unit_price,
    ]);

    // **Delete old ingredients to prevent duplication**
    $milkProduct->ingredients()->delete();

    // **Store new ingredients**
    foreach ($request->ingredients as $ingredient) {
        ingredient::create([
            'product_id' => $milkProduct->id,
            'ingredients' => $ingredient,
        ]);
    }

    return redirect()->route('milk_product.list')->with('success', 'Milk product updated successfully!');
    }


    public function view(MilkProduct $milkProduct)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        return view('milk_products.view',['milkProduct'=>$milkProduct]);
    }

    public function destroy(MilkProduct $milkProduct)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $milkProduct->delete();
        return redirect()->route('milk_product.list');
    }
}
