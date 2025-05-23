<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MilkProduct;
use App\Models\RetailorOrder;
use App\Models\RetailorOrderItems;
use Illuminate\Support\Facades\DB;

class RetailorOrderController extends Controller
{
    //

    public function index()
    {
        $retailor_orders=RetailorOrder::with(['retailor_order_item'])->get();

        return view('retailor_orders.index',['retailor_orders'=>$retailor_orders]);

    }
    
    public function create()
    {
        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.create',['milk_products'=>$milk_products]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'delivery_address'=>'required',

            'product_id'=>'required|array',
            'product_id.*'=>'required|exists:milk_products,id',

            'ordered_quantity'=>'required|array',
            'ordered_quantity.*'=>'required|numeric|min:1',
            

        ]);

                // Start transaction
            DB::beginTransaction();

            // Create the order
            $order = new RetailorOrder();

            $order->retailor_id = auth()->id();
            $order->delivery_address = $request->delivery_address;
        
            $order->ordered_date=now();

            $order->status = 'Pending';
            $order->total_amount = 0;
            $order->save();

            $totalAmount = 0;

            $errors = [];

            foreach ($request->product_id as $index => $productId) 
            {
                $product = MilkProduct::findOrFail($productId);
                $orderedQuantity = $request->ordered_quantity[$index];
                
               
                
        
                // Create order item
                $orderItem = new RetailorOrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $productId;
                $orderItem->ordered_quantity = $orderedQuantity;
                $orderItem->unit_price = $product->unit_price;
                $orderItem->save();
        
                $totalAmount += $product->unit_price * $orderedQuantity;
            }
        
            // Update the order with calculated total amount
            $order->total_amount = $totalAmount;
            $order->save();
        
            // Commit transaction if everything is successful
            DB::commit();

            return redirect()->route('retailor_order_items.list')->with('success', 'Retailor Order Saved successfully.');
    }

    public function view(RetailorOrder $retailororder)
    {
        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.view',['milk_products'=>$milk_products,'retailororder'=>$retailororder, 'order_items' => $retailororder->retailor_order_item]);
    }

    public function edit(RetailorOrder $retailororder)
    {
        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.edit',['milk_products'=>$milk_products,'retailororder'=>$retailororder, 'order_items' => $retailororder->retailor_order_item]);
    }

    public function update(RetailorOrder $retailororder,Request $request)
    {
        $request->validate([

            'delivery_address'=>'required',

            'product_id'=>'required|array',
            'product_id.*'=>'required|exists:milk_products,id',

            'ordered_quantity'=>'required|array',
            'ordered_quantity.*'=>'required|numeric|min:1',
        ]);

        $errors = [];

         // Start transaction
        DB::beginTransaction();


        $retailororder->update([

            'delivery_address'=>$request->delivery_address,
            'retailor_id'=>auth()->id(),
            'ordered_date'=>now(),
            'status'=>'Pending',
            'total_amount'=> 0
        ]);

         // Delete old order items
        $retailororder->retailor_order_item()->delete();

        $totalAmount = 0;


        foreach ($request->product_id as $index => $productId) 
        {
            $product = MilkProduct::findOrFail($productId);
            $orderedQuantity = $request->ordered_quantity[$index];
            
           
            $retailororder->retailor_order_item()->create([
                'product_id'=>$productId,
                'ordered_quantity'=>$orderedQuantity,
                'unit_price'=>$product->unit_price,
                'order_id'=>$retailororder->id
            ]);
    
        
            $totalAmount += $product->unit_price * $orderedQuantity;
        }

        $retailororder->update(['total_amount'=>$totalAmount]);

            // Commit transaction if everything is successful
            DB::commit();

        return redirect()->route('retailor_order_items.list')->with('success', 'Retailor Order updated successfully.');
    }
}
