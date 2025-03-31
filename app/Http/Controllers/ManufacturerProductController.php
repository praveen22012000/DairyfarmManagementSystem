<?php

namespace App\Http\Controllers;
use App\Models\MilkProduct;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\Manufacturer;
use App\Models\ManufacturerProduct;

use Illuminate\Support\Facades\Validator;

class ManufacturerProductController extends Controller
{
    //

    public function index()
    {
        $manufacturerProducts=ManufacturerProduct::with(['user','milk_product'])->get();


        return view('manufacturer_products.index',['manufacturerProducts'=>$manufacturerProducts]);
        

    }

    public function create()
    {

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();

    
        return view('manufacturer_products.create',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors]);



    }

    public function store(Request $request)
    {
       
        $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'time' => 'required',
            'enter_by' => 'required',
            'product_id' => 'required|array',
            'product_id.*' => 'required',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'manufacture_date' => 'required|array', // Validate that manufacture_date is an array
            'manufacture_date.*' => 'required|date', // Validate each element in the array as a date
            'expire_date' => 'required|array', // Validate that expire_date is an array
            'expire_date.*' => 'required|date', // Validate each element in the array as a date
            'user_id' => 'required|array',
            'user_id.*' => 'required|exists:users,id',

        
        ]);
    
    

        $products=$request->product_id;
        $quantities=$request->quantity;
        $manufactureDates=$request->manufacture_date;
        $expireDates=$request->expire_date;
        $users=$request->user_id;
    
        $manufacturer=Manufacturer::create([
            'date'=>$request->date,
            'time'=>$request->time,
            'enter_by'=>$request->enter_by
        ]);

        foreach ( $products as $index => $product) {
          
    
            // Save the milk consumption record
            ManufacturerProduct::create([
                'manufacturer_id' => $manufacturer->id,
         
                'product_id' => $request->product_id[$index],

                'quantity' => $quantities[$index],
                'initial_quantity_of_product'=>$quantities[$index],
                'stock_quantity'=>$quantities[$index],
                
                'manufacture_date'=>$manufactureDates[$index],
                'expire_date'=>$expireDates[$index],
                'user_id'=>$users[$index],
              
            ]);

        }

        return redirect()->route('manufacture_product.index')->with('success', 'Manufacture Milk product saved successfully!');
    }

    public function edit(ManufacturerProduct $manufacturerProduct)
    {
    

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();



        return view('manufacturer_products.edit',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors,'manufacturerProduct'=>$manufacturerProduct]);

    }

    public function update(Request $request,ManufacturerProduct $manufacturerProduct)
    {
        
        $data=$request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'time' => 'required',
            'enter_by' => 'required',
            'product_id' => 'required',
          
            'quantity' => 'required|numeric|min:1',
          
            'manufacture_date' => 'required|date', 
         
            'expire_date' => 'required|date', 
         
            'user_id' => 'required|exists:users,id',
          
            
           
        ]);

        $manufacturerProduct->manufacturer->update([
            'date'=>$request->date,
            'time'=>$request->time,
            'enter_by'=>$request->enter_by
        ]);

    //    $manufacturerProduct->manufacturer_id= $data['manufacturer_id'];
        $manufacturerProduct->product_id= $data['product_id'];
        $manufacturerProduct->manufacture_date= $data['manufacture_date'];
        $manufacturerProduct->expire_date= $data['expire_date'];
        $manufacturerProduct->user_id= $data['user_id'];
      
        



         // Calculate already consumed milkproduct
         $consumedProduct = $manufacturerProduct->initial_quantity_of_product - $manufacturerProduct->stock_quantity;

         // Update Quantity_Liters and initial_quantity_liters
         $manufacturerProduct->quantity = $request->quantity;
         $manufacturerProduct->initial_quantity_of_product = $request->quantity;
 
         // Update stock quantity without affecting already consumed milk
         $manufacturerProduct->stock_quantity = max($request->quantity - $consumedProduct, 0);
 
         $manufacturerProduct->save();
 


  
        return redirect()->route('manufacture_product.index')->with('success', 'Manufacturer Product record updated successfully!');
    }

    public function view(ManufacturerProduct $manufacturerProduct)
    {
    

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();



        return view('manufacturer_products.view',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors,'manufacturerProduct'=>$manufacturerProduct]);

    }

    public function destroy(ManufacturerProduct $manufacturerProduct)
    {
        $manufacturerProduct->delete();

        return redirect()->route('manufacture_product.index');
    }

}
