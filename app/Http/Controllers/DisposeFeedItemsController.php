<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseFeedItems;
use App\Models\DisposeFeedDetaills;
use App\Models\DisposeFeedItems;
use Illuminate\Support\Facades\Auth;

class DisposeFeedItemsController extends Controller
{
    //

    public function index()
    {
      $disposeFeedItems=DisposeFeedItems::with(['dispose_feed_details','purchase_feed_items'])->get();

      return view('dispose_feed_items.index',['disposeFeedItems'=>$disposeFeedItems]);
    }


    public function create()
    {
        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>',0)->get();

      
       return view('dispose_feed_items.create',['purchaseFeedItems'=>$purchaseFeedItems]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'dispose_date'=>'required',
            'dispose_time'=>'required',

            'purchase_feed_item_id'=>'required|array',
            'purchase_feed_item_id.*'=>'required',

            'dispose_quantity'=>'required|array',
            'dispose_quantity.*'=>'required|numeric|min:1',

            'reason_for_dispose'=>'required',
            'reason_for_dispose.*'=>'required'
        ]);

        $purchaseFeedItems=$request->purchase_feed_item_id;
        $disposeQuantities=$request->dispose_quantity;
        $reasonForDisposes=$request->reason_for_dispose;

        $invalidRows=[];
        $errors=[];

        // First, check if any row exceeds stock quantity
        foreach ($purchaseFeedItems as $index => $purchaseFeedItem) 
        {
            $disposePurchaseFeedItem = PurchaseFeedItems::findOrFail($request->purchase_feed_item_id[$index]);

            if ($disposePurchaseFeedItem && $disposeQuantities[$index] > $disposePurchaseFeedItem->stock_quantity) 
            {
                //  $invalidRows[] = $index + 1; // Store row number for error message
                $errors["dispose_quantity.$index"] = "The entered quantity ($disposeQuantities[$index]) exceeds the available stock ($disposePurchaseFeedItem->stock_quantity).";
                 
            }
        }

        if (!empty($errors)) 
        {
        return redirect()->back()->withErrors($errors)->withInput();
        }

        $disposeFeedDetail=DisposeFeedDetaills::create([
            'dispose_date'=>$request->dispose_date,
            'dispose_time'=>$request->dispose_time,
            'user_id'=>Auth::id(),

        ]);


        foreach($purchaseFeedItems as $index => $purchaseFeedItem)
        {
                 
        // Retrieve the production milk record
        $disposePurchaseFeedItem = PurchaseFeedItems::findOrFail($purchaseFeedItem);


        if ($disposePurchaseFeedItem) {
            // Deduct stock quantity
            $disposePurchaseFeedItem->stock_quantity -= $disposeQuantities[$index];
            $disposePurchaseFeedItem->save();
        }
       

       
         DisposeFeedItems::create([
            'purchase_feed_item_id'=>$request->purchase_feed_item_id[$index],
            'dispose_feed_detail_id'=>$disposeFeedDetail->id,
            'dispose_quantity'=>$disposeQuantities[$index],
            'reason_for_dispose'=>$reasonForDisposes[$index]
        ]);

        }

        return redirect()->route('dispose_feed_items.list')->with('success', 'Dispose Feed Record saved successfully.');
    }

    public function view(DisposeFeedItems $disposefeeditem)
    {
        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>=',0)->get();

      
        return view('dispose_feed_items.view',['purchaseFeedItems'=>$purchaseFeedItems,'disposefeeditem'=>$disposefeeditem]);

    }

    public function edit(DisposeFeedItems $disposefeeditem)
    {

        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>=',0)->get();

      
        return view('dispose_feed_items.edit',['purchaseFeedItems'=>$purchaseFeedItems,'disposefeeditem'=>$disposefeeditem]);

    }

    public function update(Request $request, DisposeFeedItems $disposefeeditem)
    {
    $data = $request->validate([
        'dispose_date' => 'required',
        'dispose_time' => 'required',
        'purchase_feed_item_id' => 'required',
        'dispose_quantity' => 'required|numeric|min:1',
        'reason_for_dispose' => 'required',
    ]);

    // Calculate available stock by adding back the original disposed quantity
    $availableStock = $disposefeeditem->purchase_feed_items->stock_quantity + $disposefeeditem->dispose_quantity;

    if ($request->dispose_quantity > $availableStock) {
        $errors["dispose_quantity"] = "The entered quantity exceeds the available stock quantity.";
        return redirect()->back()->withErrors($errors)->withInput();
    }

    // Update dispose details
    $disposefeeditem->dispose_feed_details->update([
        'dispose_date' => $request->dispose_date,
        'dispose_time' => $request->dispose_time,
        'user_id' => Auth::id()
    ]);

    // Calculate new stock quantity
    $newStockQuantity = $availableStock - $request->dispose_quantity;

    // Update stock quantity
    $disposefeeditem->purchase_feed_items->update([
        'stock_quantity' => $newStockQuantity
    ]);

    // Update the dispose record
    $disposefeeditem->update($data);

    return redirect()->route('dispose_feed_items.list')->with('success', 'Dispose Feed Item Record updated successfully.');
    }


   
    

    public function destroy(DisposeFeedItems $disposefeeditem)
    {


        $disposefeeditem->delete();
        return redirect()->route('dispose_feed_items.list');
    }
}
