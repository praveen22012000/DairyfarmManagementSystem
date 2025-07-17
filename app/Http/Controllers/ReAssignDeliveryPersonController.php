<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\FarmLabore;
use App\Mail\ReAssignDeliveryPersonNotification;
use Illuminate\Support\Facades\Mail;

class ReAssignDeliveryPersonController extends Controller
{
    //
    

    public function showReAssignDeliveryPersonForm($id)
    {
        
        $retailor_order = RetailorOrder::findOrFail($id);
        $deliveryPersons = FarmLabore::where('status', 'Available')
                                      ->get();

                               
        return view('re_assign_delivery_person.create',['retailor_order'=>$retailor_order,'deliveryPersons'=>$deliveryPersons]);
    }

    public function reassignDeliveryPerson(Request $request, $orderId)
    {
        $request->validate
        ([
        'delivery_person_id' => 'required|exists:farm_labores,id',
        ]);

            $order = RetailorOrder::findOrFail($orderId);

        // Step 1: If there was a previously assigned delivery person, mark them as 'Available' again
            if ($order->delivery_person_id) 
            {
                $oldDeliveryPerson = FarmLabore::find($order->delivery_person_id);
                if ($oldDeliveryPerson) 
                {
                    $oldDeliveryPerson->status = 'Available';
                    $oldDeliveryPerson->save();
                }
            }

            // Step 2: Assign new delivery person
            $newDeliveryPerson = FarmLabore::findOrFail($request->delivery_person_id);
            $newDeliveryPerson->status = 'Busy';
            $newDeliveryPerson->save();

            // Step 3: Update order with new delivery person
            $order->delivery_person_id = $newDeliveryPerson->id;
            $order->status = 'Assigned'; // Optional, or keep current status
            $order->save();
 

            // 7. Redirect back with success message
              Mail::to('pararajasingampraveen22@gmail.com')->send(new ReAssignDeliveryPersonNotification($order));

    return redirect()->route('retailor_order_items.list')->with('success', 'New Delivery person assigned successfully!');
    }

}
