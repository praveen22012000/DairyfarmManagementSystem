<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisposeMilkProducts;
use App\Models\ManufacturerProduct;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class DisposeMilkProductsController extends Controller
{


  public function monthlyReport(Request $request)
  {
    //this get the year input from the form
    $year = $request->input('year');

    // Prepare empty data
    // This creates an associative array with month names as keys and 0 as the initial value for each.
    $monthlyData = array_fill_keys([
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ], 0);

    // Get distinct years for dropdown
    // This retrieves all distinct years from the date column in the dispose_milk table
    $years = DisposeMilkProducts::selectRaw('YEAR(date) as year')
        ->distinct() //Removes duplicate years.
        ->orderBy('year', 'desc') //Orders years in descending order (latest year first).
        ->pluck('year');//Returns only the year values as a simple array, like [2024, 2023, 2022].


    if ($year) //This checks if the user has selected a year
    {
        $records = DisposeMilkProducts::whereYear('date', $year)->get();//Retrieve Records for the Selected Year

        //Loop Through Records and Sum Monthly Totals
        foreach ($records as $record) 
        {
            //This line is used to extract the month name (like January, February, etc.) from a date stored in $record->date
            $month = Carbon::parse($record->date)->format('F');

            //Add the disposed milk quantity to the respective month in the $monthlyData array.
            $monthlyData[$month] += $record->dispose_quantity;
        }
    }

    return view('dispose_milk_products.monthly_report', compact('monthlyData', 'year', 'years'));
    }





    //
    public function index()
    {
      $disposeMilkProducts=DisposeMilkProducts::with(['manufacture_proudct','user'])->get();

      return view('dispose_milk_products.index',['disposeMilkProducts'=>$disposeMilkProducts]);
    }

    public function create()
    {

      $manufacturedMilkProducts=ManufacturerProduct::where('stock_quantity','>',0)->get();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      
       return view('dispose_milk_products.create',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors]);
    }

    public function store(Request $request)
    {
  


      $request->validate([

        'manufacturer_product_id'=>'required|exists:manufacturer_products,id',
        'user_id'=>'required|exists:users,id',
        'date'=>'required',
        'dispose_quantity'=>'required|numeric|min:1',
        'reason_for_dispose'=>'required'

        ]);

        // Retrieve the production milk record
        $disposeManufacturerProduct = ManufacturerProduct::findOrFail($request->manufacturer_product_id);

         // Check if stock is sufficient for disposal
         if ($request->dispose_quantity > $disposeManufacturerProduct->stock_quantity) 
         {
             return redirect()->back()->withInput()->withErrors([
                 'dispose_quantity' => 'Not enough milk product stock available for disposal.'
                 ]);
         }

          // Deduct the disposed quantity from stock
          $disposeManufacturerProduct->decrement('stock_quantity', $request->dispose_quantity);

           // Create a new disposal record
           DisposeMilkProducts::create([

            'manufacturer_product_id'=>$request->manufacturer_product_id,
            'user_id'=>$request->user_id,
            'date'=>$request->date,
            'dispose_quantity'=>$request->dispose_quantity,
            'reason_for_dispose'=>$request->reason_for_dispose

            ]);

        return redirect()->route('dispose_milk_product.index')->with('success', 'Dispose Milk Product saved successfully.');

    }

    public function view(DisposeMilkProducts $disposeMilkProducts)
    {
      $manufacturedMilkProducts=ManufacturerProduct::where('stock_quantity','>',0)->get();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      return view('dispose_milk_products.view',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors,'disposeMilkProducts'=>$disposeMilkProducts]);


    }
    

    public function edit(DisposeMilkProducts $disposeMilkProducts)
    {

      $manufacturedMilkProducts=ManufacturerProduct::where('stock_quantity','>=',0)->get();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      return view('dispose_milk_products.edit',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors,'disposeMilkProducts'=>$disposeMilkProducts]);


    }

    public function update(Request $request,DisposeMilkProducts $disposeMilkProducts)
    {
        $data=$request->validate([

          'manufacturer_product_id'=>'required|exists:manufacturer_products,id',
          'user_id'=>'required|exists:users,id',
          'date'=>'required',
          'dispose_quantity'=>'required|numeric|min:1',
          'reason_for_dispose'=>'required'
  

        ]);

        $newDisposeQuantity=$disposeMilkProducts->manufacture_proudct->stock_quantity+$disposeMilkProducts->dispose_quantity-$request->dispose_quantity;

        $disposeMilkProducts->manufacture_proudct->update([
          'stock_quantity'=>$newDisposeQuantity
      ]);

      $disposeMilkProducts->update($data);

      return redirect()->route('dispose_milk_product.index')->with('success', 'Dispose Milk Product saved successfully.');

    }

    public function destroy(DisposeMilkProducts $disposeMilkProducts)
    {
      
      $disposeMilkProducts->delete();

        return redirect()->route('dispose_milk_product.index');
    }

   public function getStockQuantityDetails($manufacturerProductId)
    {
       
        //fetch the milk product details of the particular product
        $maufacturerProduct = ManufacturerProduct::find($manufacturerProductId);



        if ( $maufacturerProduct) {
            // Return the manufacturerProduct as JSON
            return response()->json([
                'stock_quantity' => $maufacturerProduct->stock_quantity, // Calving date equals birthdate
               
            ]);
        }
    
        return response()->json(['error' => 'manufacturerProduct not found'], 404);
    }
  }
