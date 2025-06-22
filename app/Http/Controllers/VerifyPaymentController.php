<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\OrderPayment;

class VerifyPaymentController extends Controller
{
  
    public function show($orderID)
    {
        $retailor_order = RetailorOrder::with(['order_payment'])->findOrFail($orderID);

       
        return view('verify_payment.create',['retailor_order'=>$retailor_order]);

    }

    public function verifyPaymentAccept($orderId)
    {
        $order = RetailorOrder::findOrFail($orderId);

        $order->payment_status = 'Paid';
        $order->status = 'Ready for Delivery'; // optional
       
        $order->save();

        return redirect()->route('retailor_order_items.list')->with('success', 'Payment verified successfully.');
    }

    public function verifyPaymentReject(Request $request, $orderId)
    {
        $order = RetailorOrder::findOrFail($orderId);

        $order->payment_status = 'Rejected';
        $order->status='Rejected';
        $order->save();

        return redirect()->route('retailor_order_items.list')->with('error', 'Payment rejected successfully.');
    }


}
