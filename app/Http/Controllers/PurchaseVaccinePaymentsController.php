<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseVaccinePayments;
use App\Models\PurchaseVaccine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PurchaseVaccinePaymentsController extends Controller
{
    //
     //this function used to calculate the total purchase payment for the particular purchase_id
    public function getPaymentAmount($id)
    {
        $purchase = PurchaseVaccine::with('purchase_vaccine_items')->find($id); // gets the record with the matching ID.

        if (!$purchase) // This line checks if a valid purchase was found.
        {
        return response()->json(['amount' => 0]);// This returns a JSON response with a key called amount and sets its value to 0.
        }

        $totalAmount = 0; // this line initializes a variable called $totalAmount and sets it to 0

        foreach ($purchase->purchase_vaccine_items as $item) // This starts a foreach loop.
        {
        $totalAmount += $item->unit_price * $item->purchase_quantity; // this line calculates the  totalAmount value
        }

        return response()->json(['amount' => $totalAmount]); //After calculating the total for all items, this line sends a JSON response back to the browser or frontend
    }

     //this function is used to download the payment slip later
    public function downloadPaymentSlip($id)
    {
        $payment = PurchaseVaccinePayments::with('purchase_vaccine','user')->findOrFail($id);

   

        $pdf = \PDF::loadView('payment_slips.index_vaccine_payment', [
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

        $purchase_vaccine_payments = PurchaseVaccinePayments::with('purchase_vaccine')->get();

        return view('purchase_vaccine_payments.index',['purchase_vaccine_payments'=>$purchase_vaccine_payments]);
    }


    public function create()
    {

        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        //this gets the Ids of the paid purchase_vaccine_ids
        $paid_purchase_Vaccine_Ids=PurchaseVaccinePayments::pluck('purchase_id');

       
      
        // this gets the un paid purchase vaccines ids.this will be sent to the create.blade.php
        $unpaid_purchase_vaccine_Query=PurchaseVaccine::whereNotIn('id', $paid_purchase_Vaccine_Ids);

       
        //it gets the unpaid vaccine items
        $unpaid_purchase_vaccines=$unpaid_purchase_vaccine_Query->get();

  

        return view('purchase_vaccine_payments.create',['unpaid_purchase_vaccines'=>$unpaid_purchase_vaccines]);
    }


    public function store(Request $request)
    {

         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'purchase_id' => 'required|exists:purchase_vaccines,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

        $purchase_vaccine = PurchaseVaccine::findOrfail($request->purchase_id);

        foreach($purchase_vaccine->purchase_vaccine_items as $index => $item)
        {
            if($item->manufacture_date > $request->payment_date)
            {
                return back()->withInput()->withErrors(['payment_date'=> $request->payment_date .'should not before than  vaccine name '.$item->vaccine->vaccine_name.' manufacture date'.$item->manufacture_date ]);
            }
        }

                // Generate Reference Number (Example logic)
            $nextPaymentId = \DB::table('purchase_vaccine_payments')->max('id') + 1;//"Find the maximum (highest) value in the id column of the purchase_vaccine_payments table."This adds 1 to the highest ID, giving the next ID you can use manually.
            $referenceNumber = 'TXN-' . now()->year . '-' . str_pad($nextPaymentId, 3, '0', STR_PAD_LEFT);//The function str_pad() is used to pad a string with extra characters so it reaches a certain length.

            // Create and save the payment record in the database
            $payment = new PurchaseVaccinePayments();

            $payment->purchase_id = $request->purchase_id;
            $payment->payment_amount = $request->payment_amount;
            $payment->payment_date = $request->payment_date;
            $payment->payment_receiver = auth()->id();
            $payment->reference_number = $referenceNumber;
   
            $payment->save();

            // Retrieve relevant data for the payment slip
            $purchase = PurchaseVaccine::find($request->purchase_id);
            $user = User::find(auth()->id());

            $pdf = \PDF::loadView('payment_slips.vaccine_payment', [
                'payment' => $payment,
                'purchase' => $purchase,
                'user' => $user
            ]);

              return $pdf->download('payment_slip_for_vaccine_payment' . $referenceNumber . '.pdf');
           //return redirect()->route('purchase_vaccine_payments.list')->with('success', 'Purchase vaccine payment record saved successfully!');
    }

    public function edit(Request $request,PurchaseVaccinePayments $purchasevaccinepayment)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $paid_purchase_vaccine_Ids = PurchaseVaccinePayments::pluck('purchase_id');

        $paid_purchase_vaccine_Query = PurchaseVaccine::whereIn('id',$paid_purchase_vaccine_Ids);

         $paid_purchase_vaccines=$paid_purchase_vaccine_Query->get();

        // dd($paid_purchase_vaccines);

         $purchase_vaccine_payments = PurchaseVaccinePayments::with(['user','purchase_vaccine'])->get();

     
        return view('purchase_vaccine_payments.edit',['purchasevaccinepayment'=>$purchasevaccinepayment,'paid_purchase_vaccines'=>$paid_purchase_vaccines,'purchase_vaccine_payments'=>$purchase_vaccine_payments]);


    }

     public function update(Request $request,PurchaseVaccinePayments $purchasevaccinepayment)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

         $data=$request->validate([
            'purchase_id' => "required|exists:purchase_vaccines,id|unique:purchase_vaccine_payments,purchase_id,$purchasevaccinepayment->id",
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

          $purchasevaccinepayment->update($data);

        return redirect()->route('purchase_vaccine_payments.list')->with('success', 'Purchase vaccine payment record updated successfully!');

    }

    public function view(Request $request,PurchaseVaccinePayments $purchasevaccinepayment)
    {
         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $paid_purchase_vaccine_Ids = PurchaseVaccinePayments::pluck('purchase_id');

        $paid_purchase_vaccine_Query = PurchaseVaccine::whereIn('id',$paid_purchase_vaccine_Ids);

         $paid_purchase_vaccines=$paid_purchase_vaccine_Query->get();

         $purchase_vaccine_payments = PurchaseVaccinePayments::with(['user','purchase_vaccine'])->get();

          return view('purchase_vaccine_payments.view',['purchasevaccinepayment'=>$purchasevaccinepayment,'paid_purchase_vaccines'=>$paid_purchase_vaccines,'purchase_vaccine_payments'=>$purchase_vaccine_payments]);


    }

    public function destroy(PurchaseVaccinePayments $purchasevaccinepayment)
    {
        if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $purchasevaccinepayment->delete();

        return redirect()->route('purchase_vaccine_payments.list');

    }
}
