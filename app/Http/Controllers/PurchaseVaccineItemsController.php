<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Vaccine;

use App\Models\PurchaseVaccine;
use App\Models\PurchaseVaccineItems;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseVaccineItemsController extends Controller
{
    //
    public function purchaseVaccineReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $purchaseVaccineData = [];

        if($start && $end)
        {

            $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $purchaseVaccineData = DB::table('purchase_vaccine_items')
                                ->join('purchase_vaccines','purchase_vaccine_items.purchase_id','=','purchase_vaccines.id')
                                ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
                                ->whereBetween('purchase_vaccines.purchase_date',[$start,$end])
                                ->select('vaccines.vaccine_name', DB::raw('SUM(purchase_vaccine_items.purchase_quantity) as total_purchase_quantity'))
                                ->groupBy('purchase_vaccine_items.vaccine_id','vaccines.vaccine_name')
                                ->get();
        }


         return view('reports.purchase_vaccine', compact('purchaseVaccineData', 'start', 'end'));
    }

    public function purchaseVaccineReportDownloadPDF(Request $request)
    {
         $start = $request->start_date;
        $end = $request->end_date;

        $purchaseVaccineData = [];

        if($start && $end)
        {

            $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $purchaseVaccineData = DB::table('purchase_vaccine_items')
                                ->join('purchase_vaccines','purchase_vaccine_items.purchase_id','=','purchase_vaccines.id')
                                ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
                                ->whereBetween('purchase_vaccines.purchase_date',[$start,$end])
                                ->select('vaccines.vaccine_name', DB::raw('SUM(purchase_vaccine_items.purchase_quantity) as total_purchase_quantity'))
                                ->groupBy('purchase_vaccine_items.vaccine_id','vaccines.vaccine_name')
                                ->get();
        }

         $pdfInstance = Pdf::loadView('reports_pdf.purchase_vaccine_report_pdf', compact('purchaseVaccineData', 'start', 'end'));
         return $pdfInstance->download('Purchase Vaccine Items Report.pdf');
    }


    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $purchaseVaccineItems=PurchaseVaccineItems::with(['purchase_vaccine','vaccine'])->get();

        return view('purchase_vaccine_items_by_suppliers.index',['purchaseVaccineItems'=>$purchaseVaccineItems]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $suppliers=Supplier::all();

        $vaccines=Vaccine::all();


        return view('purchase_vaccine_items_by_suppliers.create',['suppliers'=>$suppliers,'vaccines'=>$vaccines]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $request->validate([

            'supplier_id'=>'required|exists:suppliers,id',
            'purchase_date'=>'required|before_or_equal:today',
           

            'vaccine_id'=>'required|array',
            'vaccine_id.*'=>'required',

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

        $vaccines = $request->vaccine_id;
        $manufacture_dates = $request->manufacture_date;
   
        foreach( $vaccines as $index => $vaccine )
        {

                if($request->purchase_date < $manufacture_dates[$index])
                {
                    $vaccine = Vaccine::findOrFail($vaccine);

                        return back()->withInput()->withErrors
                         ([
                                'purchase_date' => 'The purchase date (' . $request->purchase_date . ') for feed "' . $vaccine->vaccine_name . '" should not be earlier than its manufacture date (' . $manufacture_dates[$index] . ').'
                        ]);
                }
        }

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


        $vaccines=$request->vaccine_id;
        $unitPrices=$request->unit_price;
        $purchaseQuantities=$request->purchase_quantity;
        $manufactureDates=$request->manufacture_date;
        $expireDates=$request->expire_date;

        $purchaseVaccine=PurchaseVaccine::create([
            'supplier_id'=>$request->supplier_id,
            'purchase_date'=>$request->purchase_date,
            'user_id'=>Auth::id(),


        ]);

        foreach ( $vaccines as $index => $vaccine)
        {
            PurchaseVaccineItems::create([
                'purchase_id'=>$purchaseVaccine->id,
                'vaccine_id'=>$request->vaccine_id[$index],
                'unit_price'=> $unitPrices[$index],
                'purchase_quantity'=>$purchaseQuantities[$index],
                'initial_quantity'=>$purchaseQuantities[$index],
                'stock_quantity'=>$purchaseQuantities[$index],


                'manufacture_date'=>$manufactureDates[$index],
                'expire_date'=>$expireDates[$index]
            ]);
        }

        return redirect()->route('purchase_vaccine_items.list')->with('success', 'Consumed Feed record updated successfully!');

    }

    public function edit(PurchaseVaccineItems $purchasevaccineitem)
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $suppliers=Supplier::all();

        $vaccines=Vaccine::all();


        return view('purchase_vaccine_items_by_suppliers.edit',['suppliers'=>$suppliers,'vaccines'=>$vaccines,'purchasevaccineitem'=>$purchasevaccineitem]);

    }


    public function update(Request $request,PurchaseVaccineItems $purchasevaccineitem)
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $data=$request->validate([

            'supplier_id'=>'required|exists:suppliers,id',
            'purchase_date'=>'required',
    
            'vaccine_id'=>'required',
            

            'unit_price'=>'required|numeric|min:1',
            'purchase_quantity'=>'required|numeric|min:1',
            

            'manufacture_date'=>'required',
           'expire_date'=>'required',
          

        ]);

        $purchasevaccineitem->purchase_vaccine->update([
            'supplier_id'=>$request->supplier_id,
            'purchase_date'=>$request->purchase_date,
            'user_id'=>Auth::id(),
        ]);


        $purchasevaccineitem->vaccine_id= $data['vaccine_id'];
        $purchasevaccineitem->unit_price=$data['unit_price'];
        $purchasevaccineitem->manufacture_date=$data['manufacture_date'];
        $purchasevaccineitem->expire_date=$data['expire_date'];

        // Calculate already consumed feed
        $consumedFeed = $purchasevaccineitem->initial_quantity - $purchasevaccineitem->stock_quantity;

         // Update purchase quantity and initial_quantity
         $purchasevaccineitem->purchase_quantity = $request->purchase_quantity;
         $purchasevaccineitem->initial_quantity = $request->purchase_quantity;

         // Update stock quantity without affecting already consumed milk
         $purchasevaccineitem->stock_quantity = max($request->purchase_quantity - $consumedFeed, 0);

         $purchasevaccineitem->save();

 
         return redirect()->route('purchase_vaccine_items.list')->with('success', 'Purchase Vaccine record updated successfully!');


    }

    public function view(PurchaseVaccineItems $purchasevaccineitem)
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        
        $suppliers=Supplier::all();

        $vaccines=Vaccine::all();


        return view('purchase_vaccine_items_by_suppliers.view',['suppliers'=>$suppliers,'vaccines'=>$vaccines,'purchasevaccineitem'=>$purchasevaccineitem]);

    }

    public function destroy(PurchaseVaccineItems $purchasevaccineitem)
    {
        if (!in_array(Auth::user()->role_id, [1, 6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $purchasevaccineitem->delete();
        return redirect()->route('purchase_vaccine_items.list');
    }
}
