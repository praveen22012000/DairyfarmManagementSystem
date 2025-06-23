<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AnimalDetail;
use App\Models\Feed;
use App\Models\PurchaseFeedItems;
use App\Models\FeedConsumeDetails;
use App\Models\FeedConsumeItems;
use Illuminate\Support\Facades\Auth;

class FeedConsumeItemsController extends Controller
{
    //
    public function index()
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $feedConsumeItems=FeedConsumeItems::with(['feed_consume_details','feed','purchase_feed_items'])->get();

        return view('feed_consumption.index',['feedConsumeItems'=>$feedConsumeItems]);
    }

    public function create()
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $animals=AnimalDetail::all();

        $feeds=Feed::all();

        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>',0)->get();

        return view('feed_consumption.create',['animals'=>$animals,'feeds'=>$feeds,'purchaseFeedItems'=>$purchaseFeedItems]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([

            'animal_id'=>'required|exists:animal_details,id',
            'date'=>'required',
            'time'=>'required',

            'consumed_quantity'=>'required|array',
            'consumed_quantity.*'=>'required',

            'notes'=>'required|array',
            'notes.*'=>'required',

            'feed_id'=>'required|array',
            'feed_id.*'=>'required|exists:feeds,id',

            'purchase_feed_item_id'=>'required|array',
            'purchase_feed_item_id.*'=>'required|exists:purchase_feed_items,id',

        
        ]);

      

        $consumeQunatities=$request->consumed_quantity;
        $notes=$request->notes;
        $feedIds=$request->feed_id;
        $purchaseFeedItems=$request->purchase_feed_item_id;

        $invalidRows=[];
        $errors=[];

        foreach($purchaseFeedItems as $index=>$purchaseFeedItem)
        {
            $consumePurchaseFeedItem=PurchaseFeedItems::findOrFail($request->purchase_feed_item_id[$index]);

            if ($consumePurchaseFeedItem && $consumeQunatities[$index] > $consumePurchaseFeedItem->stock_quantity) 
            {
                //  $invalidRows[] = $index + 1; // Store row number for error message
                $errors["consume_quantity.$index"] = "The entered quantity ($consumePurchaseFeedItem[$index]) exceeds the available stock ($consumePurchaseFeedItem->stock_quantity).";
                 
            }
        }

        if (!empty($errors)) 
        {
        return redirect()->back()->withErrors($errors)->withInput();
        }

        
        $feedConsumeDetail=FeedConsumeDetails::create([
            'animal_id'=>$request->animal_id,
            'date'=>$request->date,
            'time'=>$request->time,
            'user_id'=>Auth::id(),

        ]);

        foreach($purchaseFeedItems as $index => $purchaseFeedItem)
        {
                 
        // Retrieve the production milk record
        $consumePurchaseFeedItem=PurchaseFeedItems::findOrFail($request->purchase_feed_item_id[$index]);


        if ($consumePurchaseFeedItem) {
            // Deduct stock quantity
            $consumePurchaseFeedItem->stock_quantity -= $consumeQunatities[$index];
            $consumePurchaseFeedItem->save();
        }
       

       
         FeedConsumeItems::create([
            'purchase_feed_item_id'=>$request->purchase_feed_item_id[$index],
            'feed_consume_detail_id'=>$feedConsumeDetail->id,
            'consumed_quantity'=>$consumeQunatities[$index],
            'feed_id'=>$feedIds[$index],
            'notes'=>$notes[$index]
        ]);

        }

    }

    public function view(FeedConsumeItems $feedconsumeitem)
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $animals=AnimalDetail::all();

        $feeds=Feed::all();

        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>=',0)->get();

        return view('feed_consumption.view',['animals'=>$animals,'feeds'=>$feeds,'purchaseFeedItems'=>$purchaseFeedItems,'feedconsumeitem'=>$feedconsumeitem]);


    }

    public function edit(FeedConsumeItems $feedconsumeitem)
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $animals=AnimalDetail::all();

        $feeds=Feed::all();

        $purchaseFeedItems=PurchaseFeedItems::where('stock_quantity','>=',0)->get();

        return view('feed_consumption.edit',['animals'=>$animals,'feeds'=>$feeds,'purchaseFeedItems'=>$purchaseFeedItems,'feedconsumeitem'=>$feedconsumeitem]);

    }

    public function update(Request $request,FeedConsumeItems $feedconsumeitem)
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([
            'animal_id'=>'required|exists:animal_details,id',
            'date'=>'required',
            'time'=>'required',

            'consumed_quantity'=>'required',
            'notes'=>'required',
            'feed_id'=>'required|exists:feeds,id',
            'purchase_feed_item_id'=>'required|exists:purchase_feed_items,id'
        ]);

         // Calculate available stock by adding back the original disposed quantity
        $availableStock = $feedconsumeitem->purchase_feed_items->stock_quantity + $feedconsumeitem->consumed_quantity;

    
     

            if ($request->consumed_quantity > $availableStock) 
            {
                $errors["consumed_quantity"] = "The entered quantity exceedsss the available stock quantity.";
                return redirect()->back()->withErrors($errors)->withInput();
            }


             // Update consumed details
             $feedConsumeDetail=$feedconsumeitem->feed_consume_details->update([
                    'animal_id'=>$request->animal_id,
                    'date' => $request->date,
                    'time' => $request->time,
                    'user_id' => Auth::id()
            ]);

            // Calculate new stock quantity
            $newStockQuantity = $availableStock - $request->consumed_quantity;

             // Update stock quantity
             $feedconsumeitem->purchase_feed_items->update([
                    'stock_quantity' => $newStockQuantity
            ]);

            
            // Update the consumed record
            $feedconsumeitem->update($data);

            return redirect()->route('feed_consume_items.list')->with('success', 'Consumed Feed Item Record updated successfully.');
    }

    public function destroy(FeedConsumeItems $feedconsumeitem)
    {
         if (!in_array(Auth::user()->role_id, [1,6,5])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $feedconsumeitem->delete();

        return redirect()->route('feed_consume_items.list');
    }
}
