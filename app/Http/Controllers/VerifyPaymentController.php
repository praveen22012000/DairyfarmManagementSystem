<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerifyPaymentAcceptNotification;
use App\Mail\VerifyPaymentRejectNotification;
use Illuminate\Support\Facades\Mail;

class VerifyPaymentController extends Controller
{
  
    public function show($orderID)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $retailor_order = RetailorOrder::with(['order_payment'])->findOrFail($orderID);

       
        return view('verify_payment.create',['retailor_order'=>$retailor_order]);

    }

    public function verifyPaymentAccept($orderId)
    {
         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $order = RetailorOrder::findOrFail($orderId);

        $order->payment_status = 'Paid';
        $order->status = 'Ready for Delivery'; // optional
       
        $order->save();

           // 7. Redirect back with success message
              Mail::to($order->user->email)->send(new VerifyPaymentAcceptNotification($order));

        return redirect()->route('retailor_order_items.list')->with('success', 'Payment verified successfully.');
    }

    public function verifyPaymentReject(Request $request, $orderId)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $order = RetailorOrder::findOrFail($orderId);

        $order->payment_status = 'Unpaid';
        $order->status='Approved';
        $order->save();

               Mail::to($order->user->email)->send(new VerifyPaymentRejectNotification($order));

        return redirect()->route('retailor_order_items.list')->with('error', 'Payment rejected successfully.');
    }


}
