<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DisposeVaccineDetails;
use App\Models\Vaccine;
use App\Models\DisposeVaccineItems;
use App\Models\PurchaseVaccineItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockVaccineNotification;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class DisposeVaccineItemsController extends Controller
{
    //
    public function DisposeVaccineItemsReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $disposeVaccineData = [];

        if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $disposeVaccineData = DB::table('dispose_vaccine_items')
            ->join('dispose_vaccine_details','dispose_vaccine_items.dispose_vaccine_detail_id','=','dispose_vaccine_details.id')
            ->join('purchase_vaccine_items','dispose_vaccine_items.purchase_vaccine_item_id','=','purchase_vaccine_items.id')
            ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
             ->whereBetween('dispose_vaccine_details.dispose_date', [$start, $end])
             ->select(
            'vaccines.vaccine_name',
            DB::raw('SUM(dispose_vaccine_items.dispose_quantity) as total_dispose_quantity')
        )->groupBy('vaccines.vaccine_name')
        ->get();
        }
          return view('reports.dispose_vaccine', compact('disposeVaccineData', 'start', 'end'));
    }

    public function DisposeVaccineItemsReportPDFDownload(Request $request)
    {
         $start = $request->start_date;
        $end = $request->end_date;

        $disposeVaccineData = [];

        if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $disposeVaccineData = DB::table('dispose_vaccine_items')
            ->join('dispose_vaccine_details','dispose_vaccine_items.dispose_vaccine_detail_id','=','dispose_vaccine_details.id')
            ->join('purchase_vaccine_items','dispose_vaccine_items.purchase_vaccine_item_id','=','purchase_vaccine_items.id')
            ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
             ->whereBetween('dispose_vaccine_details.dispose_date', [$start, $end])
             ->select(
            'vaccines.vaccine_name',
            DB::raw('SUM(dispose_vaccine_items.dispose_quantity) as total_dispose_quantity')
        )->groupBy('vaccines.vaccine_name')
        ->get();
        }

         $pdfInstance = Pdf::loadView('reports_pdf.dispose_vaccine_pdf', compact('disposeVaccineData', 'start', 'end'));
         return $pdfInstance->download('Dispose Vaccine Items Report.pdf');
    }

    public function index()
    {
    
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 


      $disposeVaccineItems=DisposeVaccineItems::with(['dispose_vaccine_details','purchase_vaccine_items'])->get();

      return view('dispose_vaccine_items.index',['disposeVaccineItems'=>$disposeVaccineItems]);
    }

    

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $purchaseVaccineItems=PurchaseVaccineItems::where('stock_quantity','>',0)->get();

      
       return view('dispose_vaccine_items.create',['purchaseVaccineItems'=>$purchaseVaccineItems]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

      

        $request->validate([

            'dispose_date'=>'required',
            'dispose_time'=>'required|date_format:H:i',

            'purchase_vaccine_item_id'=>'required|array',
            'purchase_vaccine_item_id.*'=>'required',

            'dispose_quantity'=>'required|array',
            'dispose_quantity.*'=>'required|numeric|min:1',

            'reason_for_dispose'=>'required|array',
            'reason_for_dispose.*'=>'required'
        ]);

        $purchaseVaccineItems=$request->purchase_vaccine_item_id;
        $disposeQuantities=$request->dispose_quantity;
        $reasonForDisposes=$request->reason_for_dispose;

        foreach ($purchaseVaccineItems as $index => $purchaseVaccineItem) 
        {
                $purchaseVaccineItemModel = PurchaseVaccineItems::findOrFail($purchaseVaccineItem);

                if ($purchaseVaccineItemModel->manufacture_date > $request->dispose_date) 
                {
                        return back()->withInput()->withErrors
                        ([
                                'dispose_date' => 'The dispose date (' . $request->dispose_date . ') should not be earlier than the manufacture date (' . $purchaseVaccineItemModel->manufacture_date . ') of the vaccine ' . $purchaseVaccineItemModel->vaccine->vaccine_name . '.'
                        ]);
                }
        }

        
        $invalidRows=[];
        $errors=[];

         // First, check if any row exceeds stock quantity
         foreach ($purchaseVaccineItems as $index => $purchaseVaccineItem) 
         {
            $disposePurchaseVaccineItem = PurchaseVaccineItems::findOrFail($request->purchase_vaccine_item_id[$index]);
 
             if ($disposePurchaseVaccineItem && $disposeQuantities[$index] > $disposePurchaseVaccineItem->stock_quantity) 
             {
                 //  $invalidRows[] = $index + 1; // Store row number for error message
                 $errors["dispose_quantity.$index"] = "The entered quantity ($disposeQuantities[$index]) exceeds the available stock ($disposePurchaseVaccineItem->stock_quantity).";
                  
             }
         }

         if (!empty($errors)) 
        {
        return redirect()->back()->withErrors($errors)->withInput();
        }

        $disposeVaccineDetail=DisposeVaccineDetails::create([
            'dispose_date'=>$request->dispose_date,
            'dispose_time'=>$request->dispose_time,
            'user_id'=>Auth::id(),

        ]);

        foreach($purchaseVaccineItems as $index => $purchaseVaccineItem)
        {
                 
                // Retrieve the production milk record
                $disposePurchaseVaccineItem = PurchaseVaccineItems::findOrFail($purchaseVaccineItem);


                if ($disposePurchaseVaccineItem) 
                {
                    // Deduct stock quantity
                    $disposePurchaseVaccineItem->stock_quantity -= $disposeQuantities[$index];
                    $disposePurchaseVaccineItem->save();
                }
       

       
            DisposeVaccineItems::create
            ([
                    'purchase_vaccine_item_id'=>$request->purchase_vaccine_item_id[$index],
                    'dispose_vaccine_detail_id'=>$disposeVaccineDetail->id,
                    'dispose_quantity'=>$disposeQuantities[$index],
                    'reason_for_dispose'=>$reasonForDisposes[$index]
            ]);

        }

        $VaccineIds = Vaccine::pluck('id');

        foreach($VaccineIds as $vaccine_id)
        {
         
            $vaccine = Vaccine::findOrFail($vaccine_id);
            $availableStock = PurchaseVaccineItems::where('vaccine_id',$vaccine_id)->sum('stock_quantity');

            if($availableStock  < 5)
            {
                 Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockVaccineNotification($vaccine,$availableStock));
            }
        }
           return redirect()->route('dispose_vaccine_items.list')->with('success', 'Dispose vaccine record stored successfully.');

    }

    public function view(DisposeVaccineItems $disposevaccineitem)
    {
         if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $purchaseVaccineItems=PurchaseVaccineItems::where('stock_quantity','>=',0)->get();

      
        return view('dispose_vaccine_items.view',['purchaseVaccineItems'=>$purchaseVaccineItems,'disposevaccineitem'=>$disposevaccineitem]);

    }


    public function edit(DisposeVaccineItems $disposevaccineitem)
    {
         if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $purchaseVaccineItems=PurchaseVaccineItems::where('stock_quantity','>=',0)->get();

      
        return view('dispose_vaccine_items.edit',['purchaseVaccineItems'=>$purchaseVaccineItems,'disposevaccineitem'=>$disposevaccineitem]);
    }


    public function update(Request $request, DisposeVaccineItems $disposevaccineitem)
    {
         if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 
    $data = $request->validate([
        'dispose_date' => 'required|before_or_equal:today',
        'dispose_time' => 'required',
        'purchase_vaccine_item_id' => 'required',
        'dispose_quantity' => 'required|numeric|min:1',
        'reason_for_dispose' => 'required',
    ]);

    $purchaseVaccineItemModel = PurchaseVaccineItems::findOrFail($request->purchase_vaccine_item_id);

    //dd($purchaseVaccineItemModel);

                if ($purchaseVaccineItemModel->manufacture_date > $request->dispose_date) 
                {
                        return back()->withInput()->withErrors
                        ([
                                'dispose_date' => 'The dispose date (' . $request->dispose_date . ') should not be earlier than the manufacture date (' . $purchaseVaccineItemModel->manufacture_date . ') of the vaccine ' . $purchaseVaccineItemModel->vaccine->vaccine_name . '.'
                        ]);
                }
    

    // Calculate available stock by adding back the original disposed quantity
    $availableStock = $disposevaccineitem->purchase_vaccine_items->stock_quantity + $disposevaccineitem->dispose_quantity;



    if ($request->dispose_quantity > $availableStock) {
        $errors["dispose_quantity"] = "The entered quantity exceeds the available stock quantity.";
        return redirect()->back()->withErrors($errors)->withInput();
    }

    // Update dispose details
    $disposevaccineitem->dispose_vaccine_details->update([
        'dispose_date' => $request->dispose_date,
        'dispose_time' => $request->dispose_time,
        'user_id' => Auth::id()
    ]);

    // Calculate new stock quantity
    $newStockQuantity = $availableStock - $request->dispose_quantity;

    // Update stock quantity
    $disposevaccineitem->purchase_vaccine_items->update
    ([
        'stock_quantity' => $newStockQuantity
    ]);

    // Update the dispose record
    $disposevaccineitem->update($data);

    return redirect()->route('dispose_vaccine_items.list')->with('success', 'Dispose Vaccine Item Record updated successfully.');
    }


    public function destroy(DisposeVaccineItems $disposevaccineitem)
    {
         if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $disposevaccineitem->delete();
        return redirect()->route('dispose_vaccine_items.list');
    }
    
}
