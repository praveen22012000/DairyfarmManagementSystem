<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DisposeVaccineDetails;

use App\Models\DisposeVaccineItems;
use App\Models\PurchaseVaccineItems;
use Illuminate\Support\Facades\Auth;

class DisposeVaccineItemsController extends Controller
{
    //

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
            'dispose_time'=>'required',

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


        if ($disposePurchaseVaccineItem) {
            // Deduct stock quantity
            $disposePurchaseVaccineItem->stock_quantity -= $disposeQuantities[$index];
            $disposePurchaseVaccineItem->save();
        }
       

       
         DisposeVaccineItems::create([
            'purchase_vaccine_item_id'=>$request->purchase_vaccine_item_id[$index],
            'dispose_vaccine_detail_id'=>$disposeVaccineDetail->id,
            'dispose_quantity'=>$disposeQuantities[$index],
            'reason_for_dispose'=>$reasonForDisposes[$index]
        ]);

        }
 

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
        'dispose_date' => 'required',
        'dispose_time' => 'required',
        'purchase_vaccine_item_id' => 'required',
        'dispose_quantity' => 'required|numeric|min:1',
        'reason_for_dispose' => 'required',
    ]);

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
    $disposevaccineitem->purchase_vaccine_items->update([
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
