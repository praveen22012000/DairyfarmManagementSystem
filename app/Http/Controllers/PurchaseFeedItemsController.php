<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Feed;
use App\Models\PurchaseFeed;
use App\Models\PurchaseFeedItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseFeedItemsController extends Controller
{

    //old don't use 
    public function monthlyFeedPurchaseReport(Request $request)
    {
    // Default to current year
    $year = $request->input('year', now()->year);

    // Get all available years for dropdown
    $years = DB::table('purchase_feeds')
        ->selectRaw('YEAR(purchase_date) as year')
        ->groupBy('year')
        ->orderByDesc('year')
        ->pluck('year');

    // Get all feed names
    $feeds = Feed::pluck('feed_name');

    // Initialize monthly data
    $tableData = [];

    foreach (range(1, 12) as $month) {
        $row = ['month' => date('F', mktime(0, 0, 0, $month, 1))];

        foreach ($feeds as $feedName) {
            $row[$feedName] = PurchaseFeedItems::whereHas('purchase_feed', function ($query) use ($year, $month) {
                $query->whereYear('purchase_date', $year)
                      ->whereMonth('purchase_date', $month);
            })->whereHas('feed', function ($query) use ($feedName) {
                $query->where('feed_name', $feedName);
            })->sum('purchase_quantity');
        }

        $tableData[] = $row;
    }

    // Calculate yearly totals per feed
    $totalPerFeed = [];
    foreach ($feeds as $feedName) {
        $totalPerFeed[$feedName] = PurchaseFeedItems::whereHas('purchase_feed', function ($query) use ($year) {
            $query->whereYear('purchase_date', $year);
        })->whereHas('feed', function ($query) use ($feedName) {
            $query->where('feed_name', $feedName);
        })->sum('purchase_quantity');
    }

    // Pass data to the view
    return view('purchase_feed_items_by_suppliers.monthly_feed_purchases', compact('tableData', 'feeds', 'year', 'years', 'totalPerFeed'));
    }

    // old code.don't code
    public function monthlyFeedPurchaseCostReport(Request $request)
    {
    $year = $request->input('year', now()->year);

    // Get years for dropdown
    $years = DB::table('purchase_feeds')
        ->selectRaw('YEAR(purchase_date) as year')
        ->groupBy('year')
        ->orderByDesc('year')
        ->pluck('year');

    // Initialize arrays
    $monthlyCost = [];
    $tableData = [];

    foreach (range(1, 12) as $month) {
        $totalSpent = PurchaseFeedItems::whereHas('purchase_feed', function ($query) use ($year, $month) {
            $query->whereYear('purchase_date', $year)
                  ->whereMonth('purchase_date', $month);
        })->selectRaw('SUM(unit_price * purchase_quantity) as total')
          ->value('total') ?? 0;

        $monthName = date('F', mktime(0, 0, 0, $month, 1));
        $monthlyCost[] = $totalSpent;
        $tableData[] = [
            'month' => $monthName,
            'amount_spent' => $totalSpent,
        ];
    }

  

    return view('purchase_feed_items_by_suppliers.monthly_feed_spending_for_each_product', compact('tableData', 'monthlyCost', 'year', 'years'));
    }

    //new code use below

    public function purchaseFeedReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $purchaseFeedData = [];

        if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $purchaseFeedData = DB::table('purchase_feed_items')
            ->join('purchase_feeds','purchase_feed_items.purchase_id','=','purchase_feeds.id')
            ->join('feeds','purchase_feed_items.feed_id','=','feeds.id')
             ->whereBetween('purchase_feeds.purchase_date', [$start, $end])
             ->select(
            'feeds.feed_name',
            DB::raw('SUM(purchase_feed_items.purchase_quantity) as total_purchase_quantity')
        )->groupBy('purchase_feed_items.feed_id','feeds.feed_name')
        ->get();
        }

       
            return view('reports.purchase_feed', compact('purchaseFeedData', 'start', 'end'));
    }


    public function downloadPDFforPurchaseFeed(Request $request)
    {
             $start = $request->start_date;
             $end = $request->end_date;

            $purchaseFeedData = [];

        if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

                $purchaseFeedData = DB::table('purchase_feed_items')
                                    ->join('purchase_feeds','purchase_feed_items.purchase_id','=','purchase_feeds.id')
                                    ->join('feeds','purchase_feed_items.feed_id','=','feeds.id')
                                    ->whereBetween('purchase_feeds.purchase_date', [$start, $end])
                                    ->select(
                                                'feeds.feed_name',
                                                DB::raw('SUM(purchase_feed_items.purchase_quantity) as total_purchase_quantity')
                                            )->groupBy('purchase_feed_items.feed_id','feeds.feed_name')
                                    ->get();
        }

         $pdfInstance = Pdf::loadView('reports_pdf.purchase_feed_report_pdf', compact('purchaseFeedData', 'start', 'end'));
         return $pdfInstance->download('Purchase Feed Items Report.pdf');
    }






    //
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $purchaseFeedItems=PurchaseFeedItems::with(['purchase_feed','feed'])->get();

        return view('purchase_feed_items_by_suppliers.index',['purchaseFeedItems'=>$purchaseFeedItems]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.create',['suppliers'=>$suppliers,'feeds'=>$feeds]);

    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $request->validate([

            'supplier_id'=>'required|exists:suppliers,id',
            'purchase_date'=>'required|before_or_equal:today',
           

            'feed_id'=>'required|array',
            'feed_id.*'=>'required',

            'unit_price'=>'required|array',
            'unit_price.*'=>'required|numeric|min:1',

            'purchase_quantity'=>'required|array',
            'purchase_quantity.*'=>'required|numeric|min:1',

            'manufacture_date'=>'required|array',
            'manufacture_date.*'=>'required|date|before_or_equal:today',

            'expire_date'=>'required|array',
            'expire_date.*'=>'required'
        ]);

            // Step 2: Custom validation for matching indexes


            $feeds = $request->feed_id;
            $manufacture_dates = $request->manufacture_date;
            
          
            foreach ($feeds as $index => $feed) 
            {
                if ($manufacture_dates[$index] > $request->purchase_date) 
                {
                    $feed = Feed::findOrFail($feed);
                         return back()->withInput()->withErrors
                         ([
                                'purchase_date' => 'The purchase date (' . $request->purchase_date . ') for feed "' . $feed->feed_name . '" should not be earlier than its manufacture date (' . $manufacture_dates[$index] . ').'
                        ]);
                }
            }

        $errors = [];

        foreach ($request->input('expire_date') as $index => $expireDate) 
        {
                $manufactureDate = $request->input('manufacture_date')[$index] ?? null;

                if ($manufactureDate && $expireDate) 
                {
                    if (Carbon::parse($expireDate)->lt(Carbon::parse($manufactureDate))) 
                    {
                        $errors["expire_date.$index"] = "The Expiry Date must be after or equal to the Manufacture Date.";
                    }
                }
        }

        // If custom validation fails, return with errors
        if (!empty($errors)) 
        {
            return redirect()->back()
                                    ->withErrors($errors)
                                    ->withInput();
        }




        $feeds=$request->feed_id;
        $unitPrices=$request->unit_price;
        $purchaseQuantities=$request->purchase_quantity;
        $manufactureDates=$request->manufacture_date;
        $expireDates=$request->expire_date;

        $purchaseFeed=PurchaseFeed::create([
            'supplier_id'=>$request->supplier_id,
            'purchase_date'=>$request->purchase_date,
            'user_id'=>Auth::id(),


        ]);

        foreach ( $feeds as $index => $feed)
        {
            PurchaseFeedItems::create([
                'purchase_id'=>$purchaseFeed->id,
                'feed_id'=>$request->feed_id[$index],
                'unit_price'=> $unitPrices[$index],
                'purchase_quantity'=>$purchaseQuantities[$index],
                'initial_quantity'=>$purchaseQuantities[$index],
                'stock_quantity'=>$purchaseQuantities[$index],


                'manufacture_date'=>$manufactureDates[$index],
                'expire_date'=>$expireDates[$index]
            ]);
        }

        
        return redirect()->route('purchase_feed_items.list')->with('success', 'Purchase Feed Item record saved successfully!');
    }

    public function edit(PurchaseFeedItems $purchasefeeditem)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.edit',['suppliers'=>$suppliers,'feeds'=>$feeds,'purchasefeeditem'=>$purchasefeeditem]);

    }

    public function update(Request $request,PurchaseFeedItems $purchasefeeditem)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $data=$request->validate([

            'supplier_id'=>'required|exists:suppliers,id',
            'purchase_date'=>'required',
    
            'feed_id'=>'required',
            

            'unit_price'=>'required|numeric|min:1',
            'purchase_quantity'=>'required|numeric|min:1',
            

            'manufacture_date'=>'required',
           'expire_date'=>'required',
          

        ]);

        $purchasefeeditem->purchase_feed->update([
            'supplier_id'=>$request->supplier_id,
            'purchase_date'=>$request->purchase_date,
            'user_id'=>Auth::id(),
        ]);


        $purchasefeeditem->feed_id= $data['feed_id'];
        $purchasefeeditem->unit_price=$data['unit_price'];
        $purchasefeeditem->manufacture_date=$data['manufacture_date'];
        $purchasefeeditem->expire_date=$data['expire_date'];

        // Calculate already consumed feed
        $consumedFeed = $purchasefeeditem->initial_quantity - $purchasefeeditem->stock_quantity;

         // Update purchase quantity and initial_quantity
         $purchasefeeditem->purchase_quantity = $request->purchase_quantity;
         $purchasefeeditem->initial_quantity = $request->purchase_quantity;

         // Update stock quantity without affecting already consumed milk
         $purchasefeeditem->stock_quantity = max($request->purchase_quantity - $consumedFeed, 0);

         $purchasefeeditem->save();

 
         return redirect()->route('purchase_feed_items.list')->with('success', 'Consumed Feed record updated successfully!');


    }

    public function view(PurchaseFeedItems $purchasefeeditem)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.view',['suppliers'=>$suppliers,'feeds'=>$feeds,'purchasefeeditem'=>$purchasefeeditem]);
    }

    public function destroy(PurchaseFeedItems $purchasefeeditem)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        
        $purchasefeeditem->delete();
        return redirect()->route('purchase_feed_items.list');
    }
}

