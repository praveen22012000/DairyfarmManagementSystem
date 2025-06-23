<?php

namespace App\Http\Controllers;
use App\Models\MilkProduct;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\Manufacturer;
use App\Models\ManufacturerProduct;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class ManufacturerProductController extends Controller
{
    //
    /*
    public function report(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = ManufacturerProduct::with('milk_product'); // eager load product relationship

        if ($year && $month) 
        {
        $query->whereYear('manufacture_date', $year)
              ->whereMonth('manufacture_date', $month);
        }

        $manufacturedData = $query->get();

        // Calculate totals per product
        $report = $manufacturedData->groupBy('product_id')->map(function ($group) {
            return [
            'product_name' => $group->first()->milk_product->product_name,
            'total_quantity' => $group->sum('quantity'),
            ];
        });

        return view('manufacturer_products.report', compact('report'));
    }*/

    /*the following code is work but comment it for testing chart*/
    public function monthlyReport(Request $request)
    {
     $year = $request->input('year', now()->year);

    $manufacturedData = ManufacturerProduct::with('milk_product')
        ->whereYear('manufacture_date', $year)
        ->get();

    $productsByMonth = [];

    foreach ($manufacturedData as $record) 
    {
        
        $month = Carbon::parse($record->manufacture_date)->format('F');
        $productName = $record->milk_product->product_name;

        if (!isset($productsByMonth[$productName])) 
        {
            $productsByMonth[$productName] = array_fill_keys([
                'January','February','March','April','May','June',
                'July','August','September','October','November','December'
            ], 0);
        }

        $productsByMonth[$productName][$month] += $record->quantity;
    }

    return view('manufacturer_products.monthly_report', compact('productsByMonth', 'year'));
    }

    
    /*
public function monthlyReport(Request $request)
{
    $year = $request->input('year', now()->year);
    $products = ManufacturerProduct::distinct()->pluck('product_name');

   

    $charts = [];

    foreach ($products as $product) {
        // Get monthly totals for this product
        $monthlyData = ManufacturerProduct::select(
                DB::raw('MONTH(manufacture_date) as month'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->where('product_name', $product)
            ->whereYear('manufacture_date', $year)
            ->groupBy(DB::raw('MONTH(manufacture_date)'))
            ->get()
            ->pluck('total_quantity', 'month')
            ->toArray();

        // Prepare labels and data
        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('F');
            $data[] = $monthlyData[$i] ?? 0;
        }

        // Create chart for this product
        $charts[] = new LaravelChart([
            'chart_title' => $product . ' - Monthly Report ' . $year,
            'report_type' => 'group_by_string',
            'model' => 'App\Models\ManufacturerProduct',
            'group_by_field' => 'manufacture_date',
            'chart_type' => 'bar',
            'labels' => $labels,
            'dataset' => [
                [
                    'label' => 'Total Quantity',
                    'data' => $data,
                ],
            ],
            'chart_color' => 'rgba(75, 192, 192, 0.8)',
        ]);
    }

    return view('manufacturer_products.monthly_report', compact('charts', 'year'));
}*/




    public function showMonthlyReport(Request $request)
    {
    $year = $request->input('year', now()->year); // Default to current year if not selected

    $manufacturedData = ManufacturerProduct::with('milk_product')
        ->whereYear('manufacture_date', $year)
        ->get();

    // Create a data structure: ['Yogurt' => [Jan => 10, Feb => 5...], ...]
    $productsByMonth = [];

    foreach ($manufacturedData as $record) {
        $month = Carbon::parse($record->manufacture_date)->format('F'); // January, February etc.
        $productName = $record->milk_product->product_name;

        if (!isset($productsByMonth[$productName])) {
            $productsByMonth[$productName] = array_fill_keys([
                'January','February','March','April','May','June',
                'July','August','September','October','November','December'
            ], 0);
        }

        $productsByMonth[$productName][$month] += $record->quantity;
    }

    return view('manufacturer_products.index', compact('productsByMonth', 'year'));
    }

    public function index()
    {
        
        if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $manufacturerProducts=ManufacturerProduct::with(['user','milk_product'])->get();


        return view('manufacturer_products.index',['manufacturerProducts'=>$manufacturerProducts]);
        

    }

    public function create()
    {

        if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();

    
        return view('manufacturer_products.create',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors]);



    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
       
        $request->validate([
            'date' => ['required', 'date'],
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
         if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();



        return view('manufacturer_products.edit',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors,'manufacturerProduct'=>$manufacturerProduct]);

    }

    public function update(Request $request,ManufacturerProduct $manufacturerProduct)
    {
        if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
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
         if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();



        return view('manufacturer_products.view',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors,'manufacturerProduct'=>$manufacturerProduct]);

    }

    public function destroy(ManufacturerProduct $manufacturerProduct)
    {
         if (!in_array(Auth::user()->role_id, [1, 5, 6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $manufacturerProduct->delete();

        return redirect()->route('manufacture_product.index');
    }

}
