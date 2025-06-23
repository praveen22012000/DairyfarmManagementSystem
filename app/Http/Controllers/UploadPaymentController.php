<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\OrderPayment;
use Illuminate\Support\Str;

class UploadPaymentController extends Controller
{
    

     //create form for upload payment receipt by retailor
     public function create($orderID)
     {
        if (!in_array(Auth::user()->role_id, [1, 3])) 
        {
            abort(403, 'Unauthorized action.');
        }

         $retailor_order=RetailorOrder::findOrfail($orderID);
 
         return view('upload_payment_receipt.create',['retailor_order'=>$retailor_order]);
 
     }
 
     //store the data of the upload payment receipt by retailor
     public function store(Request $request, $id)
     {
       if (!in_array(Auth::user()->role_id, [1, 3])) 
        {
            abort(403, 'Unauthorized action.');
        }
         $transactionId = $this->generateTransactionId();

          // 1. Validate the incoming data
         $validated = $request->validate([
             'payment_receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Max 2MB
            
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
               'transaction_id'=>$transactionId, 
                'payment_date'=>now()
           
        
         ]);
 
           // 5. Optionally, update order's payment_status if you want
         $order->payment_status = 'Under Review';
 
             //6. Increase the payment_attempts by 1
         $order->payment_attempts += 1;
 
         $order->save();
 
            // 7. Redirect back with success message
         return redirect()->route('retailor_order_items.list')->with('success', 'Payment details uploaded successfully. Waiting for manager verification.');
     }


     //this function is used to generate the transaction id
    private function generateTransactionId()
    {
    $prefix = 'TRX';
    $date = now()->format('Ymd');
    $random = strtoupper(Str::random(6)); // or use a counter if you prefer

    return "{$prefix}-{$date}-{$random}";
    
    }

    //this method is used to downloade the receipt that is  opened in the newtab
    public function view(Request $request)
    {
      
        // This line gets the path parameter from the URL query string(it get path )
        // and "path" in the URL gets the value from the blade view
        //usually "path" is occurs after the url "?" symbol
        $path = $request->query('path');
      

        // Optional: Add security to prevent path traversal
        //This block of code is used to check whether a file exists in Laravel's public storage disk, and if it doesn't, it shows a 404 error page.
        if (!\Storage::disk('public')->exists($path)) {
            abort(404, 'File not found.');
        }

        $fileUrl = asset('storage/' . $path);

        return view('receipts.view', compact('fileUrl', 'path'));
    }

 //this function is used to show the edit() the payment receipt
     public function edit($orderID)
     {
         if (!in_array(Auth::user()->role_id, [1, 3])) 
        {
            abort(403, 'Unauthorized action.');
        }
           $retailor_order = RetailorOrder::with(['order_payment'])->findOrFail($orderID);
 
           return view('upload_payment_receipt.edit',['retailor_order'=>$retailor_order]);
     }

//this function is used to delete the order payment record and the payment receipt  that have already uploaded
     public function cancelPayment($id)
     {
         if (!in_array(Auth::user()->role_id, [1, 3])) 
        {
            abort(403, 'Unauthorized action.');
        }
      
        $retailor_order= RetailorOrder::with(['order_payment'])->findOrFail($id);

        $retailor_order->status='Approved';
      
        $retailor_order->payment_status='Unpaid';
        $retailor_order->save();

          $retailor_order->order_payment->delete();
        return redirect()->route('retailor_order_items.list')->with('success', 'Payment details deleted successfully.');

     }

     //
     public function update($orderID,Request $request)
     {
         if (!in_array(Auth::user()->role_id, [1, 3])) 
        {
            abort(403, 'Unauthorized action.');
        }
      
        $transactionId = $this->generateTransactionId();

          // 1. Validate the incoming data
         $validated = $request->validate([
             'payment_receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Max 2MB
            
         ]);

         // 2. Find the order
         $order = RetailorOrder::findOrFail($orderID);
 
           // 3. Handle the payment receipt upload
         if ($request->hasFile('payment_receipt')) 
         {

        //This line is uploading a file (in your case, the payment receipt image or PDF) and saving it to a folder, then storing the file path in the $receiptPath variable.
         $receiptPath = $request->file('payment_receipt')->store('payment_receipts', 'public');
         }
 
         // 4. Store the payment information in the `orderpayments` table
         $order->order_payment->update([
             'order_id' => $order->id,
             'payment_receipt' => $receiptPath ?? null,
               'transaction_id'=>$transactionId, 
                'payment_date'=>now()
           
        
         ]);
 
        
           // 5. Optionally, update order's payment_status if you want
         $order->payment_status = 'Under Review';
 
          
 
         $order->save();
 

             // 7. Redirect back with success message
         return redirect()->route('retailor_order_items.list')->with('success', 'Payment details updated succeesfully. Waiting for manager verification.');
     }
 
}
