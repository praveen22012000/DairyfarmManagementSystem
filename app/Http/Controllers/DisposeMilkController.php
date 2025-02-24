<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\ProductionMilk;
use App\Models\DisposeMilk;

class DisposeMilkController extends Controller
{
    //

    public function index()
    {
        $disposeMilks=DisposeMilk::with(['user','production_milk'])->get();


        return view('dispose_milk.index',['disposeMilks'=>$disposeMilks]);
    }

    public function create()
    {
        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();


      
       

        return view('dispose_milk.create',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors]);


    }

    public function store(Request $request)
    {

       
            // Validate request data
            $request->validate([

            'production_milk_id'=>'required|exists:production_milks,id',
            'user_id'=>'required|exists:users,id',
            'date'=>'required',
            'dispose_quantity'=>'required|numeric|min:0',
            'reason_for_dispose'=>'required'

            ]);

       

           // Retrieve the production milk record
            $productionMilk = ProductionMilk::findOrFail($request->production_milk_id);

           
      
            // Check if stock is sufficient for disposal
            if ($request->dispose_quantity > $productionMilk->stock_quantity) 
            {
                return redirect()->back()->withInput()->withErrors([
                    'dispose_quantity' => 'Not enough milk stock available for disposal.'
                    ]);
            }

            // Deduct the disposed quantity from stock
            $productionMilk->decrement('stock_quantity', $request->dispose_quantity);

            // Create a new disposal record
            DisposeMilk::create([

            'production_milk_id'=>$request->production_milk_id,
            'user_id'=>$request->user_id,
            'date'=>$request->date,
            'dispose_quantity'=>$request->dispose_quantity,
            'reason_for_dispose'=>$request->reason_for_dispose

            ]);


    }

    public function view(DisposeMilk $disposeMilk)
    {
        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();

        return view('dispose_milk.view',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors,'disposeMilk'=>$disposeMilk]);

    }

    public function edit(DisposeMilk $disposeMilk)
    {
        
        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();

        
        return view('dispose_milk.edit',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors,'disposeMilk'=>$disposeMilk]);
    }

    public function update(Request $request,DisposeMilk $disposeMilk)
    {
      
        $data=$request->validate([

            'production_milk_id'=>"required|exists:production_milks,id,$disposeMilk->id",
            'user_id'=>"required|exists:users,id",
            'date'=>'required',
            'dispose_quantity'=>'required|numeric|min:0',
            'reason_for_dispose'=>'required'

        ]);

    
     
    
        $disposeMilk->update($data);

        return redirect()->route('dispose_milk.list')->with('success', 'milk dispose record updated successfully!');
    }

    public function destroy(DisposeMilk $disposeMilk)
    {
        $disposeMilk->delete();

        return redirect()->route('dispose_milk.list');
    }

    public function getStockQuantityDetails($productionMilkId)
    {
        // Fetch the calf's details along with the parent cow (dam)
        $productionMilk = ProductionMilk::find($productionMilkId);



        if ( $productionMilk) {
            // Return the calving details as JSON
            return response()->json([
                'stock_quantity' => $productionMilk->stock_quantity, // Calving date equals birthdate
               
            ]);
        }
    
        return response()->json(['error' => 'Calf not found'], 404);
    }


}
