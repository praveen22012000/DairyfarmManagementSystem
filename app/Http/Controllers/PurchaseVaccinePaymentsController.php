<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseVaccinePayments;
use App\Models\PurchaseVaccine;

class PurchaseVaccinePaymentsController extends Controller
{
    //
     //this function used to calculate the total purchase payment for the particular purchase_id
    public function getPurchaseAmountVaccine($id)
    {
        
    // Calculate total of all purchase_feed_items for this purchase
    $totalAmount = PurchaseVaccineItems::where('purchase_id', $id)
                    ->selectRaw('SUM(unit_price * purchase_quantity) as total')
                    ->value('total');

    return response()->json(['total_amount' => $totalAmount]);

    }


      public function create()
    {
      
        $paid_purchase_Vaccine_Ids=PurchaseVaccinePayments::pluck('purchase_id');

      

        $unpaid_purchase_vaccine_Query=PurchaseVaccine::whereNotIn('id', $paid_purchase_Vaccine_Ids);

       $unpaid_purchase_vaccines=$unpaid_purchase_vaccine_Query->get();

        return view('purchase_vaccine_payments.create',['unpaid_purchase_vaccines'=>$unpaid_purchase_vaccines]);
    }


     public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_vaccine_id' => 'required|exists:purchase_vaccines,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

                // Generate Reference Number (Example logic)
            $nextPaymentId = \DB::table('purchase_vaccine_payments')->max('id') + 1;//"Find the maximum (highest) value in the id column of the purchase_vaccine_payments table."This adds 1 to the highest ID, giving the next ID you can use manually.
            $referenceNumber = 'TXN-' . now()->year . '-' . str_pad($nextPaymentId, 3, '0', STR_PAD_LEFT);//The function str_pad() is used to pad a string with extra characters so it reaches a certain length.

            // Create and save the payment record in the database
            $payment = new PurchaseVaccinePayments();

            $payment->purchase_vaccine_id = $request->purchase_vaccine_id;
            $payment->payment_amount = $request->payment_amount;
            $payment->payment_date = $request->payment_date;
            $payment->payment_receiver = auth()->id();
            $payment->reference_number = $referenceNumber;
   
            $payment->save();

            // Retrieve relevant data for the payment slip
            $purchase = PurchaseVaccine::find($request->purchase_vaccine_id);
            $user = User::find(auth()->id());

            $pdf = \PDF::loadView('payment_slips.vaccine_payment', [
                'payment' => $payment,
                'purchase' => $purchase,
                'user' => $user
            ]);


            return $pdf->download('payment_slip_' . $referenceNumber . '.pdf');
    }

}
