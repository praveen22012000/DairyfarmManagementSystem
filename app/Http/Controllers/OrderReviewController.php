<?php

namespace App\Http\Controllers;

use App\Models\RetailorOrder;
use App\Models\MilkProduct;
use App\Models\ManufacturerProduct;
use App\Models\OrderAllocation;
use App\Models\RetailorOrderItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderReviewController extends Controller
{
    /*
    public function show($orderId)
    {
        // Get the order with its items and retailer info
        $retailororder = RetailorOrder::with(['user', 'retailor_order_item'])->findOrFail($orderId);

        $milk_products=MilkProduct::with('retailor_order_item')->get();

  
        // Show the review view
        return view('retailor_order_review.review', ['retailororder'=>$retailororder,'milk_products'=>$milk_products,'order_items' => $retailororder->retailor_order_item]);
    }*/

    public function isProductAvailable($productId, $requestedQty)
    {
        // Get the total available stock for this product
        $totalStockQty = ManufacturerProduct::where('product_id', $productId)->sum('stock_quantity');
    
       

        // Get the total allocated stock (not yet delivered or completed)
        $allocatedQty = OrderAllocation::where('product_id', $productId)
            ->whereHas('order', function ($query) {
                $query->whereNotIn('status', ['Delivered', 'Completed']);
            })
            ->sum('allocated_quantity');

          
        // Calculate available stock
        $availableStock = $totalStockQty - $allocatedQty;
       
        return $availableStock >= $requestedQty; // Return true/false
    }


    public function review($orderId)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
 
        //findOrFail is a shortcut method that directly queries by the primary key, which is usually id
        //Returns a single model instance
         $retailororder = RetailorOrder::findOrFail($orderId);

        $order_items= RetailorOrderItems::where('order_id',$orderId)->get();

        $milk_products=MilkProduct::with('retailor_order_item')->get();

        // Add availability status to each item
        foreach ($order_items as $item) {
            $item->is_available = $this->isProductAvailable($item->product_id, $item->ordered_quantity);
        }

        // Return the view with the order data
        return view('retailor_order_review.review',['milk_products'=>$milk_products,'order_items' => $order_items,'retailororder'=>$retailororder ]);
    }



    public function approveOrder($orderId)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $order = RetailorOrder::with('user', 'retailor_order_item')->findOrFail($orderId);

        // First, validate all items have sufficient stock
        foreach ($order->retailor_order_item as $item) 
        {
            if (!$this->isProductAvailable($item->product_id, $item->ordered_quantity)) 
            {
                return back()->with('error', 'Not enough stock to fulfill product: ' . $item->milk_product->product_name);
            }
        }

        // If all items have sufficient stock, proceed with allocation
        foreach ($order->retailor_order_item as $item) 
        {
            $requiredQty = $item->ordered_quantity;
            $productId = $item->product_id;

            $stocks = ManufacturerProduct::where('product_id', $productId)
                   ->where('stock_quantity', '>', 0)
                   ->orderBy('created_at')
                   ->get();

            foreach ($stocks as $stock) 
            {
                if ($requiredQty <= 0) 
                {
                break;
                }

                $allocQty = min($stock->stock_quantity, $requiredQty);

                OrderAllocation::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'stock_id' => $stock->id,
                'allocated_quantity' => $allocQty,
                'delivered_quantity' => 0,
                'is_delivered' => false,
                ]);

                $requiredQty -= $allocQty;
            }
        }

        // Mark order as approved
        $order->status = 'Approved';
        $order->save();

    return redirect()->route('retailor_order_items.list')->with('success', 'Retailor Order Approved successfully.');
    }


     //  Reject the order
     public function reject($orderId)
     {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

         $order = RetailorOrder::findOrFail($orderId);
         $order->status = 'Rejected';
         $order->save();
 
         return redirect()->route('retailor_order_items.list')->with('success', 'Order rejected successfully.');
     }

   
}

