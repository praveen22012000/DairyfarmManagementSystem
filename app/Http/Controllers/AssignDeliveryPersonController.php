<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RetailorOrder;
use App\Models\FarmLabore;


class AssignDeliveryPersonController extends Controller
{
    //
    public function showDeliveryPersonForm($id)
    {
        
        $retailor_order = RetailorOrder::findOrFail($id);
        $deliveryPersons = FarmLabore::where('status', 'Available')
                                      ->get();

                               
        return view('assign_delivery_person.create',['retailor_order'=>$retailor_order,'deliveryPersons'=>$deliveryPersons]);
    }

    public function assignDeliveryPerson(Request $request,$id)
    {
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

        
    
        return redirect()->route('retailor_order_items.list')->with('success', 'Delivery person assigned successfully!');
   
    }
}
