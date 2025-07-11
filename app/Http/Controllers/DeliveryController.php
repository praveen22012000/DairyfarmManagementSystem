<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\FarmLabore;
use App\Models\OrderAllocation;
use App\Models\ManufacturerProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StartDeliveryNotification;
use App\Mail\DeliveryNotification;
use App\Mail\LowStockProductWhenDeliveryNotification;
use App\Models\MilkProduct;

class DeliveryController extends Controller
{
    //
    public function startDelivery($id)
    {
        if (Auth::user()->role_id !== 1 &&  !(Auth::user()->role_id === 3 && $order->user->id === Auth::id())) 
        {
            abort(403, 'Unauthorized action.');
        }

        $order = RetailorOrder::findOrFail($id);

        // Optional: check if user is the assigned delivery person
      /*  if ($order->delivery_person_id != auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }*/

        $order->status = 'Out for Delivery';
        $order->save();

          Mail::to('pararajasingampraveen22@gmail.com')->send(new StartDeliveryNotification($order));

        return redirect()->back()->with('success', 'Order marked as Out for Delivery.');
    }

    public function markAsDelivered($orderId)
    {
        if (Auth::user()->role_id !== 1 &&  !(Auth::user()->role_id === 3 && $order->user->id === Auth::id())) 
        {
            abort(403, 'Unauthorized action.');
        }
        // 1. Find the order
        $order = RetailorOrder::findOrFail($orderId);

        // 2. Get all allocations for this order
        $allocations = OrderAllocation::where('order_id', $orderId)->get();

        // 3. Deduct allocated quantity from stock
        foreach ($allocations as $allocation) 
        {

            $stock = ManufacturerProduct::find($allocation->stock_id);

            if ($stock) 
            {
                // Reduce available quantity, increase used quantity
                $stock->stock_quantity -= $allocation->allocated_quantity;
             //   $stock->quantity_used += $allocation->quantity_allocated;

                $stock->save();
            }

            $allocation->delivered_quantity = $allocation->allocated_quantity;
            $allocation->is_delivered = true;
            $allocation->save();
        }

        // 4. Update order status
        $order->status = 'Delivered';
        $order->delivered_at = now(); // optional
        $order->is_delivered = true;
        $order->save();

         //  Mark the delivery person as available again
        if ($order->delivery_person_id) 
        {
                $deliveryPerson = FarmLabore::find($order->delivery_person_id);

                if ($deliveryPerson) 
                {
                $deliveryPerson->status = 'Available';
                $deliveryPerson->save();
                }
        }

        //the below code is used for notification when low stock
            // Get all ordered items for this order
            $orderItems = $order->retailor_order_item; // plural: items

            // Extract product_ids from the collection
            $productIds = $orderItems->pluck('product_id');


            foreach($productIds as $pro_id)
            {
                $milk_product = MilkProduct::findOrfail($pro_id);

            
                $available_stock = ManufacturerProduct::where('product_id',$pro_id)->sum('stock_quantity');

                if($available_stock < 10)
                {
                     Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockProductWhenDeliveryNotification($milk_product,$available_stock,));
                }
            }

        Mail::to('pararajasingampraveen22@gmail.com')->send(new DeliveryNotification($order));

        return redirect()->back()->with('success', 'Order is successfully Delivered and stock is updated.');
    }

}
