<?php

namespace App\Http\Controllers;

use App\Models\RetailorOrder;
use App\Models\OrderAllocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CancelOrderController extends Controller
{
    //
    public function cancelOrderBeforeApproved($orderId)
    {
       
        $order = RetailorOrder::findOrFail($orderId);

         //only role_id==1(admin) and role_id=3(retailor) only orders made by them can cancel before approval
        if (Auth::user()->role_id !== 1 &&  !(Auth::user()->role_id === 3 && $order->user->id === Auth::id())) 
        {
            abort(403, 'Unauthorized action.');
        }

        // Ensure order is still pending
        if ($order->status !== 'Pending') 
        {
        return back()->with('error', 'Only pending orders can be cancelled before approval.');
        }

        // Cancel the order
        $order->status = 'canceled';
        $order->save();

        return redirect()->route('retailor_order_items.list')->with('success', 'Order cancelled successfully before approval.');
    }

    public function cancelOrderAfterApproved($orderId)
    {   
        $order = RetailorOrder::findOrFail($orderId);

          //only role_id==1(admin) and role_id=3(retailor) only orders made by them can cancel after approval
        if (Auth::user()->role_id !== 1 &&  !(Auth::user()->role_id === 3 && $order->user->id === Auth::id())) 
        {
            abort(403, 'Unauthorized action.');
        }

        // Make sure it's not already delivered
        if ($order->status === 'delivered') 
        {
        return redirect()->back()->with('error', 'Delivered orders cannot be canceled.');
        }

        // Delete order allocation (or mark as canceled if you want to keep history)
        OrderAllocation::where('order_id', $order->id)->delete();

        // Update order status
        $order->status = 'canceled';
        $order->save();

        return redirect()->route('retailor_order_items.list')->with('success', 'Order canceled successfully.');
    }

}
