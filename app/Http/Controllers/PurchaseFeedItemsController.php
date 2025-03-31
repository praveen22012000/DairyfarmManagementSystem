<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Feed;
use App\Models\PurchaseFeed;
use App\Models\PurchaseFeedItems;
use Illuminate\Support\Facades\Auth;

class PurchaseFeedItemsController extends Controller
{
    //
    public function index()
    {
        $purchaseFeedItems=PurchaseFeedItems::with(['purchase_feed','feed'])->get();

        return view('purchase_feed_items_by_suppliers.index',['purchaseFeedItems'=>$purchaseFeedItems]);
    }

    public function create()
    {
        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.create',['suppliers'=>$suppliers,'feeds'=>$feeds]);

    }

    public function store(Request $request)
    {
        $request->validate([

            'supplier_id'=>'required|exists:suppliers,id',
            'purchase_date'=>'required',
           

            'feed_id'=>'required|array',
            'feed_id.*'=>'required',

            'unit_price'=>'required|array',
            'unit_price.*'=>'required|numeric|min:1',

            'purchase_quantity'=>'required|array',
            'purchase_quantity.*'=>'required|numeric|min:1',

            'manufacture_date'=>'required|array',
            'manufacture_date.*'=>'required',

            'expire_date'=>'required|array',
            'expire_date.*'=>'required'
        ]);




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
    }

    public function edit(PurchaseFeedItems $purchasefeeditem)
    {
        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.edit',['suppliers'=>$suppliers,'feeds'=>$feeds,'purchasefeeditem'=>$purchasefeeditem]);

    }

    public function update(Request $request,PurchaseFeedItems $purchasefeeditem)
    {
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
        $suppliers=Supplier::all();

        $feeds=Feed::all();


        return view('purchase_feed_items_by_suppliers.view',['suppliers'=>$suppliers,'feeds'=>$feeds,'purchasefeeditem'=>$purchasefeeditem]);
    }

    public function destroy(PurchaseFeedItems $purchasefeeditem)
    {
        $purchasefeeditem->delete();
        return redirect()->route('purchase_feed_items.list');
    }
}

