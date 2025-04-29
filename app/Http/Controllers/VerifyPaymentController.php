<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\OrderPayment;

class VerifyPaymentController extends Controller
{
    //
    public function create($orderID)
    {
        $retailor_order=RetailorOrder::findOrfail($orderID);

        return view('upload_payment_receipt.create',['retailor_order'=>$retailor_order]);

    }

    public function store(Request $request, $id)
    {
         // 1. Validate the incoming data
        $validated = $request->validate([
            'payment_receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Max 2MB
            'transaction_id' => 'required|string|max:255',
            'payment_date' => 'required|date',
        
        ]);

        // 2. Find the order
        $order = RetailorOrder::findOrFail($id);

          // 3. Handle the payment receipt upload
        if ($request->hasFile('payment_receipt')) 
        {
        $receiptPath = $request->file('payment_receipt')->store('payment_receipts', 'public');
        }

        // 4. Store the payment information in the `orderpayments` table
        OrderPayment::create([
            'order_id' => $order->id,
            'payment_receipt' => $receiptPath ?? null,
            'transaction_id' => $validated['transaction_id'],
            'payment_date' => $validated['payment_date'],
          
       
        ]);

          // 5. Optionally, update order's payment_status if you want
        $order->payment_status = 'Under Review';
        $order->save();

           // 6. Redirect back with success message
        return redirect()->route('retailor_order_items.list')->with('success', 'Payment details uploaded successfully. Waiting for manager verification.');
    }

    public function edit($orderID)
    {
         
          $retailor_order = RetailorOrder::with(['order_payment'])->findOrFail($orderID);

       
          return view('upload_payment_receipt.edit',['retailor_order'=>$retailor_order]);
    }

    public function show($orderID)
    {
        $retailor_order = RetailorOrder::with(['order_payment'])->findOrFail($orderID);

        return view('verify_payment.create',['retailor_order'=>$retailor_order]);

    }
}
