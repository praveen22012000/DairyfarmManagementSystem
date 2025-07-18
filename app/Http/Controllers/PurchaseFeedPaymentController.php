<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchaseFeedPayment;
use App\Models\PurchaseFeed;
use App\Models\PurchaseFeedItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PurchaseFeedPaymentController extends Controller
{
    //

    //this function used to calculate the total purchase payment for the particular purchase_id
    public function getPurchaseAmount($id)
    {

       
    // Calculate total of all purchase_feed_items for this purchase
    $totalAmount = PurchaseFeedItems::where('purchase_id', $id)     //We're starting a query on the PurchaseFeedItems model
                    ->selectRaw('SUM(unit_price * purchase_quantity) as total') // selectRaw(...): Used to write raw SQL inside Laravel's query builder.  This multiplies the unit_price and purchase_quantity of each item and adds them all together
                    ->value('total');

    return response()->json(['total_amount' => $totalAmount]);

    }


    //this function is used to download the payment slip later
    public function downloadPaymentSlip($id)
    {
    $payment = PurchaseFeedPayment::with('purchase_feed','user')->findOrFail($id);

 

    $pdf = \PDF::loadView('payment_slips.index_feed_payment', [
        'payment' => $payment,
       
     //   'user' => $payment->user,
        //'reference_number'=> $payment->reference_number
    ]);

    return $pdf->download('payment_slip_' . $payment->reference_number . '.pdf');
    }

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $purchase_feed_payments= PurchaseFeedPayment::with('purchase_feed')->get();

        return view('purchase_feed_payments.index',['purchase_feed_payments'=>$purchase_feed_payments]);
    }


    public function create()
    {
         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $paid_purchase_feed_Ids=PurchaseFeedPayment::pluck('purchase_id');

        $unpaid_purchase_feed_Query=PurchaseFeed::whereNotIn('id',$paid_purchase_feed_Ids);

        $unpaid_purchase_feeds=$unpaid_purchase_feed_Query->get();

        return view('purchase_feed_payments.create',['unpaid_purchase_feeds'=>$unpaid_purchase_feeds]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'purchase_id' => 'required|exists:purchase_feeds,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required',
        ]);

                // Generate Reference Number (Example logic)
            $nextPaymentId = \DB::table('purchase_feed_payments')->max('id') + 1;
            $referenceNumber = 'TXN-' . now()->year . '-' . str_pad($nextPaymentId, 3, '0', STR_PAD_LEFT);

            // Create and save the payment record in the database
            $payment = new PurchaseFeedPayment();

            $payment->purchase_id = $request->purchase_id;
            $payment->payment_amount = $request->payment_amount;
            $payment->payment_date = $request->payment_date;
            $payment->payment_receiver = auth()->id();
            $payment->reference_number = $referenceNumber;
   
            $payment->save();

            // Retrieve relevant data for the payment slip
            $purchase = PurchaseFeed::find($request->purchase_id);
            $user = User::find(auth()->id());

            $pdf = \PDF::loadView('payment_slips.feed_payment', [
                'payment' => $payment,
                'purchase' => $purchase,
                'user' => $user
            ]);


            return $pdf->download('payment_slip_' . $referenceNumber . '.pdf');
    }

    public function edit(Request $request,PurchaseFeedPayment $purchasefeedpayment)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $paid_purchase_feed_Ids=PurchaseFeedPayment::pluck('purchase_id');

        $paid_purchase_feed_Query=PurchaseFeed::whereIn('id',$paid_purchase_feed_Ids);

        $paid_purchase_feeds=$paid_purchase_feed_Query->get();

        $purchase_feed_payments=PurchaseFeedPayment::with(['purchase_feed','user'])->get();

        return view('purchase_feed_payments.edit',['purchasefeedpayment'=>$purchasefeedpayment,'paid_purchase_feeds'=>$paid_purchase_feeds]);

    }

    public function update(Request $request,PurchaseFeedPayment $purchasefeedpayment)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data=$request->validate([
            'purchase_id' => 'required|exists:purchase_feeds,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required',
        ]);

        $purchasefeedpayment->update($data);

        
            // Redirect back with a success SweetAlert
            return redirect()->route('purchase_feed_payments.list')->with('success', 'Purchase feed payment record updated successfully!');
        
    }

    public function view(Request $request,PurchaseFeedPayment $purchasefeedpayment)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $paid_purchase_feed_Ids=PurchaseFeedPayment::pluck('purchase_id');

        $paid_purchase_feed_Query=PurchaseFeed::whereIn('id',$paid_purchase_feed_Ids);

        $paid_purchase_feeds=$paid_purchase_feed_Query->get();

        $purchase_feed_payments=PurchaseFeedPayment::with(['purchase_feed','user'])->get();

        return view('purchase_feed_payments.view',['purchasefeedpayment'=>$purchasefeedpayment,'paid_purchase_feeds'=>$paid_purchase_feeds]);
    }
   
    public function destroy(PurchaseFeedPayment $purchasefeedpayment)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $purchasefeedpayment->delete();

         return redirect()->route('purchase_feed_payments.list')->with('success', 'Purchase feed payment record deleted successfully!');

    }
}
