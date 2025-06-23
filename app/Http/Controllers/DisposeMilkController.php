<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\ProductionMilk;
use App\Models\DisposeMilk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 

class DisposeMilkController extends Controller
{
    //
    public function monthlyReport(Request $request)
    {

        ////this get the year input from the form
    $year = $request->input('year');

    // Prepare empty data
     // This creates an associative array with month names as keys and 0 as the initial value for each.
    $monthlyData = array_fill_keys([
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ], 0);

    // Get distinct years for dropdown
     // Get distinct years for dropdown
    // This retrieves all distinct years from the date column in the dispose_milk table
    $years = DisposeMilk::selectRaw('YEAR(date) as year')
        ->distinct()//Removes duplicate years.
        ->orderBy('year', 'desc') //Orders years in descending order (latest year first).
        ->pluck('year');//Returns only the year values as a simple array, like [2024, 2023, 2022].

    if ($year) //This checks if the user has selected a year 
    {
        $records = DisposeMilk::whereYear('date', $year)->get();//Retrieve Records for the Selected Year

         //Loop Through Records and Sum Monthly Totals
        foreach ($records as $record) 
        {
              //This line is used to extract the month name (like January, February, etc.) from a date stored in $record->date
            $month = Carbon::parse($record->date)->format('F');

              //Add the disposed milk quantity to the respective month in the $monthlyData array.
            $monthlyData[$month] += $record->dispose_quantity;
        }
    }

    return view('dispose_milk.monthly_report', compact('monthlyData', 'year', 'years'));
    }

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $disposeMilks=DisposeMilk::with(['user','production_milk'])->get();


        return view('dispose_milk.index',['disposeMilks'=>$disposeMilks]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();


      
       

        return view('dispose_milk.create',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors]);


    }

    public function store(Request $request)
    {


        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
       
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

            return redirect()->route('dispose_milk.list')->with('success', 'Dispose Milk record saved successfully');

    }

    public function view(DisposeMilk $disposeMilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();

        return view('dispose_milk.view',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors,'disposeMilk'=>$disposeMilk]);

    }

    public function edit(DisposeMilk $disposeMilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $ProductionsMilks = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();

        
        return view('dispose_milk.edit',['ProductionsMilks'=>$ProductionsMilks,'farm_labors'=>$farm_labors,'disposeMilk'=>$disposeMilk]);
    }

    public function update(Request $request,DisposeMilk $disposeMilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([

            'production_milk_id'=>"required|exists:production_milks,id",
            'user_id'=>"required|exists:users,id",
            'date'=>'required',
            'dispose_quantity'=>'required|numeric|min:0',
            'reason_for_dispose'=>'required'

        ]);
      
        $new_consumed_quantity =$disposeMilk->production_milk->stock_quantity+$disposeMilk->dispose_quantity-$request->dispose_quantity;

        
        $disposeMilk->production_milk->update([
            'stock_quantity'=>$new_consumed_quantity
        ]);
    
        $disposeMilk->update($data);

        return redirect()->route('dispose_milk.list')->with('success', 'milk dispose record updated successfully!');
    }

    public function destroy(DisposeMilk $disposeMilk)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $disposeMilk->delete();

        return redirect()->route('dispose_milk.list');
    }

    public function getStockQuantityDetails($productionMilkId)
    {
      
        //Fetch the milk production details of the particular milk item
        $productionMilk = ProductionMilk::find($productionMilkId);



        if ( $productionMilk) {
            
            return response()->json([
                'stock_quantity' => $productionMilk->stock_quantity, // Calving date equals birthdate
               
            ]);
        }
    
        return response()->json(['error' => 'Calf not found'], 404);
    }


}
