<?php

namespace App\Http\Controllers;

use App\Models\ProductionMilk;
use App\Models\MilkProduct;

use App\Models\ProductionSupply;
use App\Models\ProductionSupplyDetails;
use App\Models\ManufacturerProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\LowStockMilkNotification;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class ProductionSupplyDetailsController extends Controller
{
    //old don't use
    public function monthlyReport(Request $request)
    {

     //Retrieves the year value submitted from the frontend (   
    $year = $request->input('year');

    // Get years available from production_supplies
    $years = \DB::table('production_supplies')//directly accesses the production_supplies table using Laravel's query builder.
        ->selectRaw('YEAR(date) as year')//Extracts the year part from the date column and names it as year
        ->distinct()//Ensures that only unique years are returned.
        ->pluck('year');//Extracts only the values of the year column into a simple array.

        //You get something like [2022, 2023, 2024] — all years where there is supply data.


    // Monthly array default to 0
    $monthlyConsumption = array_fill_keys([ //This creates an associative array with month names as keys and 0 as the default value
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ], 0);

    if ($year) //This checks if the $year variable is not null
    {
        //"Only get the ProductionSupplyDetails if their related production_supply has a date in the given year."
        $records = ProductionSupplyDetails::with('production_supply')// Load data from the production_supply relationship (eager loading).
            ->whereHas('production_supply', function ($query) use ($year)//  Filters the details only where the related production_supply entry has the date in the selected year. 
            {
            $query->whereYear('date', $year);
            })->get();


        foreach ($records as $detail) //Loops over each detail record.
        {
            $month = Carbon::parse($detail->production_supply->date)->format('F');// Converts the date into a Carbon object Converts the date into full month name
            $monthlyConsumption[$month] += $detail->consumed_quantity;
    }

    return view('supply_manufacturing_milk.monthly_report', compact('monthlyConsumption', 'year', 'years'));
    }


}

//this function generate the report for monthly milk consumption of the each product
//old don't use
  public function productMonthlyConsumption(Request $request)
  {
    // Get the selected year or use the current year
    $year = $request->input('year', now()->year);

    // Get distinct years from production_supplies
    $years = DB::table('production_supplies')//This queries the production_supplies table.
        ->selectRaw('YEAR(date) as year')// Extracts just the year from the date field
        ->distinct()//Ensures only unique years are returned.
        ->pluck('year');//Pulls out the year values into a simple array like [2022, 2023, 2024].

    // Get monthly consumption per product
    $data = ProductionSupplyDetails::with(['production_supply', 'milk_product'])//Uses Eager Loading to load related production_supply (for date info) and milk_product (for product name).
        ->whereHas('production_supply', function ($query) use ($year) //whereYear('date', $year) ensures that only supplies from the selected year (e.g., 2024) are used.
        {
            $query->whereYear('date', $year);
        })
        ->get()//Executes the query and gets all matching records from the database.

        //This line groups all the ProductionSupplyDetails records by the month in which they were produced.
        ->groupBy(function ($item) {//Each $item here is an instance of the ProductionSupplyDetails model

            //This line takes a date from the production_supply record and returns the full month name like "January", "February"
            return Carbon::parse($item->production_supply->date)->format('F');

        })
        //This line is part of a Laravel Collection method chain. It is calling the map() method, which is used to transform each item in a collection.
        ->map(function ($groupedByMonth) {//n the first loop, $groupedByMonth will hold all the records for January.
            return $groupedByMonth->groupBy(function ($item) {//Inside the current month’s records, we want to group the data again, this time by product.
                return $item->milk_product->product_name ?? 'Unknown';//Here we access the related milk_product and get its product_name.
            })->map(function ($items) {

                //This gives us the total quantity consumed for that product in that month.
                return $items->sum('consumed_quantity');//For each product's group of records, we add up the values of consumed_quantity.

            });
        });

    // Format months and products
    $months = [
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ];

    $products = DB::table('milk_products')->pluck('product_name')->toArray();

    // Prepare table and chart data
    $tableData = [];//ou create an empty array called $tableData.
    foreach ($months as $month) {//You loop over all 12 months (which were likely defined earlier as an array):
        $row = ['month' => $month];//You start a new row for the current month.//You start a new row for the current month.
        foreach ($products as $product) {// loop through all product names
            $row[$product] = $data[$month][$product] ?? 0;//Now you fill in the quantity for that product in the current month.
        }
        $tableData[] = $row;
    }

    return view('supply_manufacturing_milk.product_monthly_consumption', compact('tableData', 'products', 'year', 'years'));
    }

//old don't use
    public function animalMilkUsageReport(Request $request)
    {
        //This reads the selected year from the request 
    $year = $request->input('year', now()->year);

    // Get distinct animal names involved in production
    $animals = ProductionSupplyDetails::join('production_milks', 'production_milks.id', '=', 'production_supply_details.production_milk_id')
        ->join('animal_details', 'animal_details.id', '=', 'production_milks.animal_id')
        ->join('production_supplies', 'production_supplies.id', '=', 'production_supply_details.production_supply_id')
        ->whereYear('production_supplies.date', $year)
        ->select('animal_details.animal_name')
        ->distinct()
        ->pluck('animal_name');

    // Get total milk consumption grouped by animal and month
    $milkData = ProductionSupplyDetails::join('production_milks', 'production_milks.id', '=', 'production_supply_details.production_milk_id')
        ->join('animal_details', 'animal_details.id', '=', 'production_milks.animal_id')
        ->join('production_supplies', 'production_supplies.id', '=', 'production_supply_details.production_supply_id')
        ->whereYear('production_supplies.date', $year)
        ->select(
            'animal_details.animal_name',
            DB::raw('MONTH(production_supplies.date) as month'),
            DB::raw('SUM(production_supply_details.consumed_quantity) as total_milk')
        )
        ->groupBy('animal_details.animal_name', DB::raw('MONTH(production_supplies.date)'))
        ->get();

    // Prepare table data with row-wise and column-wise totals
    $tableData = [];
    $totalPerMonth = array_fill(1, 12, 0); // month => total

    foreach ($animals as $animal) {
        $row = ['animal_name' => $animal];
        $rowTotal = 0;

        foreach (range(1, 12) as $month) {
            $data = $milkData->where('animal_name', $animal)->where('month', $month)->first();
            $quantity = $data ? $data->total_milk : 0;

            $row['month_' . $month] = $quantity;
            $rowTotal += $quantity;
            $totalPerMonth[$month] += $quantity;
        }

        $row['total'] = $rowTotal;
        $tableData[] = $row;
    }

    return view('supply_manufacturing_milk.animal_milk_usage', compact('tableData', 'year', 'totalPerMonth'));
    }


//this function is new
    public function allocatedMilkForEachProduct(Request $request)
    {

       
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $allocatedMilk=[];
    
      if ($startDate && $endDate) 
      {
        $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        $allocatedMilk = DB::table('production_supply_details')
        ->join('production_supplies', 'production_supply_details.production_supply_id', '=', 'production_supplies.id')
        ->join('milk_products', 'production_supply_details.product_id', '=', 'milk_products.id')
        ->whereBetween('production_supplies.date', [$startDate, $endDate])
        ->select(
            'milk_products.product_name',
            DB::raw('SUM(production_supply_details.consumed_quantity) as total_allocated_quantity')
        )
        ->groupBy('production_supply_details.product_id', 'milk_products.product_name')
        ->get();

        
        }

      
    return view('reports.allocated_milk', compact('allocatedMilk', 'startDate', 'endDate'));
        
    }
// this function is for the download PDF for the above function
    public function downloadPDFforAllocatedMilk(Request $request)
    {
         $startDate = $request->start_date;
         $endDate = $request->end_date;

           if ($startDate && $endDate) 
            {
                    $request->validate
                    ([
                        'start_date' => 'required|date',
                        'end_date' => 'required|date|after_or_equal:start_date',
                    ]);
    
                    $allocatedMilk = DB::table('production_supply_details')
                                        ->join('production_supplies', 'production_supply_details.production_supply_id', '=', 'production_supplies.id')
                                        ->join('milk_products', 'production_supply_details.product_id', '=', 'milk_products.id')
                                        ->whereBetween('production_supplies.date', [$startDate, $endDate])
                                        ->select(
                                                    'milk_products.product_name',
                                                    DB::raw('SUM(production_supply_details.consumed_quantity) as total_allocated_quantity')
                                                )
                                            ->groupBy('production_supply_details.product_id', 'milk_products.product_name')
                                            ->get();

        
            }

        $pdfInstance = Pdf::loadView('reports_pdf.allocated_milk_product_pdf', compact('allocatedMilk', 'startDate', 'endDate'));
        return $pdfInstance->download('Allocated_milk_product.pdf');
    }



    public function index()
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $productionSupply=ProductionSupply::all();

        $productionSupplyDetails=ProductionSupplyDetails::with(['production_milk','milk_product'])->get();



        return view('supply_manufacturing_milk.index',['productionSupply'=>$productionSupply,'productionSupplyDetails'=>$productionSupplyDetails]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
         // Fetch records where stock_quantity > 0
         $ProductionsMilk = ProductionMilk::where('stock_quantity', '>', 0)->get();

         $milkProducts=MilkProduct::all();

     

        return view('supply_manufacturing_milk.create',['ProductionsMilk'=>$ProductionsMilk,'milkProducts'=>$milkProducts]);

    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
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

        $consume_date= $request->date;

        foreach($productionMilkIds as $index => $productionMilkId)
        {
            $productionMilk = ProductionMilk::find($productionMilkId);

            if($productionMilk->production_date > $consume_date)
            {
                     return back()->withInput()->withErrors([
                            'date' => 'The milk consumption date cannot be earlier than the milk production date ('.$productionMilk->production_date.')',
                         ]);
            }


        }

        // Array to store invalid rows
        $invalidRows = [];

        $errors = [];

        // First, check if any row exceeds stock quantity
        foreach ($productionMilkIds as $index => $productionMilkId) 
        {
            $stockMilk = ProductionMilk::find($productionMilkId);

            if ($stockMilk && $consumedQuantities[$index] > $stockMilk->stock_quantity) 
            {
                //  $invalidRows[] = $index + 1; // Store row number for error message
                $errors["consumed_quantity.$index"] = "The entered quantity ($consumedQuantities[$index]) exceeds the available stock ($stockMilk->stock_quantity).";
           
            }
        }

   
        if (!empty($errors)) 
        {
        return redirect()->back()->withErrors($errors)->withInput();
        }
    



        // If all rows are valid, proceed with deduction
        $productionSupply = ProductionSupply::create
        ([
            'date' => $request->date,
            'time' => $request->time,
            'entered_by' => $request->entered_by,
        ]);


        foreach ($productionMilkIds as $index => $productionMilkId) 
        {
            $stockMilk = ProductionMilk::find($productionMilkId);

            if ($stockMilk) 
            {
            // Deduct stock quantity
            $stockMilk->stock_quantity -= $consumedQuantities[$index];
            $stockMilk->save();
            }

            // Save the milk consumption record
             ProductionSupplyDetails::create
             ([
                'production_milk_id' => $productionMilkId,
                'production_supply_id' => $productionSupply->id,
                'product_id' => $request->product_id[$index],
                'consumed_quantity' => $consumedQuantities[$index],
            ]);
        }

    
        $available_stock = ProductionMilk::sum('stock_quantity');

        if($available_stock < 10)
        {
             Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockMilkNotification($available_stock));

        }

        return redirect()->route('milk_allocated_for_manufacturing.index')->with('success', 'Milk consumption record saved successfully.');
    }

    public function view(ProductionSupplyDetails $productionSupplyDetails)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        

         $milkProducts=MilkProduct::all();

          // Ensure the relationship is loaded
         $productionSupplyDetails->load('production_milk', 'milk_product', 'production_supply');

      

        return view('supply_manufacturing_milk.view',['milkProducts'=>$milkProducts,'productionSupplyDetails'=>$productionSupplyDetails]);

    }

    public function edit(ProductionSupplyDetails $productionSupplyDetails)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $milkProducts=MilkProduct::all();

       $ProductionsMilk = ProductionMilk::where('stock_quantity', '>', 0)->get();

        // Ensure the relationship is loaded
        $productionSupplyDetails->load('production_milk', 'milk_product', 'production_supply');

        
        return view('supply_manufacturing_milk.edit',['milkProducts'=>$milkProducts,'productionSupplyDetails'=>$productionSupplyDetails,'ProductionsMilk'=>$ProductionsMilk]);
    }

    public function update(ProductionSupplyDetails $productionSupplyDetails,Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        

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
        if (!in_array(Auth::user()->role_id, [1, 6, 5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $productionSupplyDetails->delete();

        return redirect()->route('milk_allocated_for_manufacturing.index');
    }


    
}


