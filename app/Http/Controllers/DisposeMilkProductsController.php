<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisposeMilkProducts;
use App\Models\ManufacturerProduct;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\LowStockMilkProductNotification;
use Illuminate\Support\Facades\Mail;

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
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

      $disposeMilkProducts=DisposeMilkProducts::with(['manufacture_proudct','user'])->get();

     // dd($disposeMilkProducts);

      return view('dispose_milk_products.index',['disposeMilkProducts'=>$disposeMilkProducts]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

      $manufacturedMilkProducts=ManufacturerProduct::where('stock_quantity','>',0)->get();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      
       return view('dispose_milk_products.create',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $request->validate([

        'manufacturer_product_id'=>'required|exists:manufacturer_products,id',
        'user_id'=>'required|exists:users,id',
        'date'=>'required|before_or_equal:today',
        'dispose_quantity'=>'required|numeric|min:1',
        'reason_for_dispose'=>'required'

        ]);

        // Retrieve the production milk record
        $disposeManufacturerProduct = ManufacturerProduct::findOrFail($request->manufacturer_product_id);

       // dd($disposeManufacturerProduct);

        if($disposeManufacturerProduct->manufacture_date > $request->date)
        {
            return redirect()->back()->withInput()->withErrors([
                 'date' => 'Disposal date should not before than product manufacture date'
                 ]);
        }

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
           $dispose_milk_product = DisposeMilkProducts::create([

            'manufacturer_product_id'=>$request->manufacturer_product_id,
            'user_id'=>$request->user_id,
            'date'=>$request->date,
            'dispose_quantity'=>$request->dispose_quantity,
            'reason_for_dispose'=>$request->reason_for_dispose

            ]);

            $product_id = $disposeManufacturerProduct->product_id;



            $available_stock = ManufacturerProduct::where('product_id',$product_id)->sum('stock_quantity');


         

            if($available_stock < 10)
            {
                   Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockMilkProductNotification($available_stock,$dispose_milk_product));

            }

        return redirect()->route('dispose_milk_product.index')->with('success', 'Dispose Milk Product saved successfully.');

    }

    public function view(DisposeMilkProducts $disposeMilkProducts)
    {

        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
      $manufacturedMilkProducts=ManufacturerProduct::all();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      return view('dispose_milk_products.view',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors,'disposeMilkProducts'=>$disposeMilkProducts]);


    }
    

    public function edit(DisposeMilkProducts $disposeMilkProducts)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

      $manufacturedMilkProducts=ManufacturerProduct::where('stock_quantity','>=',0)->get();

      $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

      $farm_labors=User::where('role_id',$farm_labore_id)->get();


      return view('dispose_milk_products.edit',['manufacturedMilkProducts'=>$manufacturedMilkProducts,'farm_labors'=>$farm_labors,'disposeMilkProducts'=>$disposeMilkProducts]);


    }

    public function update(Request $request,DisposeMilkProducts $disposeMilkProducts)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

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
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

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
