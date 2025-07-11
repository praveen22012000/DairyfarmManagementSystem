<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\FarmLabore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssignDeliveryPersonNotification;

class AssignDeliveryPersonController extends Controller
{
    //
    public function showDeliveryPersonForm($id)
    {
         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $retailor_order = RetailorOrder::findOrFail($id);
        $deliveryPersons = FarmLabore::where('status', 'Available')
                                      ->get();

     

        return view('assign_delivery_person.create',['retailor_order'=>$retailor_order,'deliveryPersons'=>$deliveryPersons]);
    }

    public function assignDeliveryPerson(Request $request,$id)
    {

         if (!in_array(Auth::user()->role_id, [1, 7])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'delivery_person_id' => 'required|exists:farm_labores,id',
        ]);
    
        $retailor_order = RetailorOrder::findOrFail($id);
        $retailor_order->delivery_person_id = $request->delivery_person_id;
        $retailor_order->status = 'Assigned';
        $retailor_order->save();
    
        // Update the delivery person's status (optional)
        $deliveryPerson = FarmLabore::find($request->delivery_person_id);
        $deliveryPerson->status = 'Busy';
        $deliveryPerson->save();

           Mail::to('pararajasingampraveen22@gmail.com')->send(new AssignDeliveryPersonNotification($retailor_order));
    
        return redirect()->route('retailor_order_items.list')->with('success', 'Delivery person assigned successfully!');
   
    }
}
