<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Vaccine;

use App\Models\PurchaseVaccine;
use App\Models\PurchaseVaccineItems;
use Illuminate\Support\Facades\Auth;

class PurchaseVaccineItemsController extends Controller
{
    //
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
            'purchase_date'=>'required',
           

            'vaccine_id'=>'required|array',
            'vaccine_id.*'=>'required',

            'unit_price'=>'required|array',
            'unit_price.*'=>'required|numeric|min:1',

            'purchase_quantity'=>'required|array',
            'purchase_quantity.*'=>'required|numeric|min:1',

            'manufacture_date'=>'required|array',
            'manufacture_date.*'=>'required',

            'expire_date'=>'required|array',
            'expire_date.*'=>'required'
        ]);

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
