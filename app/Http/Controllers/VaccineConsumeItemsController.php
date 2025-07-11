<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AnimalDetail;
use App\Models\Vaccine;
use App\Models\PurchaseVaccineItems;
use App\Models\Appointment;
use App\Models\VaccineConsumeDetails;
use App\Models\VaccineConsumeItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockVaccineNotification;

class VaccineConsumeItemsController extends Controller
{
    //

    public function index()
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        $vaccineConsumeItems=VaccineConsumeItems::with(['vaccine_consume_detail','animal','vaccinations'])->get();

        return view('vaccine_consumption_details.index',['vaccineConsumeItems'=>$vaccineConsumeItems]);
    }

    public function create()
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $animals = AnimalDetail::all();
        $vaccinations = Vaccine::all();
        $vaccination_items = PurchaseVaccineItems::with(['purchase_vaccine','vaccine','vaccine_consume_items'])->get();

        // Get the currently authenticated user
        $user = auth()->user();
    
        // Get used appointment IDs to exclude
        $usedAppointmentIds = VaccineConsumeDetails::pluck('appointment_id');
    
        // Base query for appointments
        $appointmentsQuery = Appointment::whereNotIn('id', $usedAppointmentIds);
    
        // If user is not admin (role_id != 1), filter by veterinarian
        if ($user->role_id != 1) 
        {
        $appointmentsQuery->where('veterinarian_id', $user->id);
        }
    
        // Get the filtered appointments
        $appointments = $appointmentsQuery->get();

        return view('vaccine_consumption_details.create', [
        'animals' => $animals,
        'vaccinations' => $vaccinations,
        'vaccination_items' => $vaccination_items,
        'appointments' => $appointments,
        'isAdmin' => $user->role_id == 1 // Pass this to view for UI hints
        ]);


    }

    public function store(Request $request)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $request->validate
        ([
            'vaccination_date'=>'required',
            'appointment_id'=>'required|exists:appointments,id',
            
            'animal_id'=>'required|array',
            'animal_id.*'=>'required|exists:animal_details,id',

            'vaccine_id'=>'required|array',
            'vaccine_id.*'=>'required|exists:vaccines,id',

            'vaccination_item_id'=>'required|array',
            'vaccination_item_id.*'=>'required|exists:purchase_vaccine_items,id',

            'consumed_quantity'=>'required|array',
            'consumed_quantity.*'=>'required|numeric|min:1'
        ]);

        $animalIds=$request->animal_id;
        $vaccinationIds=$request->vaccine_id;
        $vaccinationItemIds=$request->vaccination_item_id;
        $consumedQunatities=$request->consumed_quantity;

        foreach($vaccinationItemIds as $index=>$vaccinationItemId)
        {
            $vaccination_item = PurchaseVaccineItems::findOrfail($vaccinationItemId);

            if($request->vaccination_date < $vaccination_item->manufacture_date)
            {
                return back()->withInput()->withErrors(['vaccination_date'=>$vaccination_item->vaccine->vaccine_name.' vaccination date should not before than vaccine manufacture date '.$vaccination_item->manufacture_date]);
            }
        }

        $invalidRows=[];
        $errors=[];

        
        //this calculation is different from other 
        // Calculate total consumption per vaccine item
        foreach ($request->vaccination_item_id as $index => $itemId) 
        {
            $quantity = (int)$request->consumed_quantity[$index];
            
            if (!isset($consumptionByItem[$itemId])) {
                $consumptionByItem[$itemId] = 0;
            }
            $consumptionByItem[$itemId] += $quantity;
        }


          // Check against available stock
        foreach ($consumptionByItem as $itemId => $totalConsumed) 
        {
            $item = PurchaseVaccineItems::findOrFail($itemId);
            if ($item->stock_quantity < $totalConsumed) {
                $errors["consumed_quantity"] = "Total consumption for vaccine item {$item->vaccine->vaccine_name} exceeds available stock (Available: {$item->stock_quantity}, Requested: {$totalConsumed})";
            }
        }

        if (!empty($errors)) 
        {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors);
        }

        $vaccineConsumeDetail=VaccineConsumeDetails::create([
            'vaccination_date'=>$request->vaccination_date,
            'appointment_id'=>$request->appointment_id,
      
        ]);

        foreach($vaccinationItemIds as $index => $vaccinationItemId)
        {
                 
        // Retrieve the production milk record
        $consumePurchaseVaccineItem=PurchaseVaccineItems::findOrFail($request->vaccination_item_id[$index]);


        if ($consumePurchaseVaccineItem) {
            // Deduct stock quantity
            $consumePurchaseVaccineItem->stock_quantity -= $consumedQunatities[$index];
            $consumePurchaseVaccineItem->save();
        }
       

       
         VaccineConsumeItems::create([
            'animal_id'=>$animalIds[$index],
            'vaccine_id'=>$vaccinationIds[$index],
            'vaccination_item_id'=>$request->vaccination_item_id[$index],
            'vaccine_consume_detail_id'=>$vaccineConsumeDetail->id,
            'consumed_quantity'=>$consumedQunatities[$index]
        ]);

        }

        $VaccineIds = Vaccine::pluck('id');

        foreach($VaccineIds as $vaccine_id)
        {
         
            $vaccine = Vaccine::findOrFail($vaccine_id);
            $availableStock = PurchaseVaccineItems::where('vaccine_id',$vaccine_id)->sum('stock_quantity');

            if($availableStock  < 5)
            {
                 Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockVaccineNotification($vaccine,$availableStock));
            }
        }
 

           return redirect()->route('vaccine_consume_items.list')->with('success', 'Vaccine Consume items record stored successfully!');

    }

    public function view(VaccineConsumeItems $vaccineconsumeitem)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $animals = AnimalDetail::all();
        $vaccinations = Vaccine::all();
        $vaccination_items = PurchaseVaccineItems::with(['purchase_vaccine','vaccine','vaccine_consume_items'])->get();

         // Get the currently authenticated user
        $user = auth()->user();

        $usedAppointmentIds = VaccineConsumeDetails::pluck('appointment_id');//this line only get the appointment_id's in VaccineConsumeDetails

        $appointmentsQuery = Appointment::whereIn('id', $usedAppointmentIds);//this 

         // If user is not admin (role_id != 1), filter by veterinarian
        if ($user->role_id != 1) 
        {
        $appointmentsQuery->where('veterinarian_id', $user->id);
        }
    
            // Get the filtered appointments
        $appointments = $appointmentsQuery->get();


        return view('vaccine_consumption_details.view',['animals'=>$animals,'vaccinations'=>$vaccinations,'vaccination_items'=>$vaccination_items,'vaccineconsumeitem'=>$vaccineconsumeitem,'appointments'=>$appointments]);
    }

    public function edit(VaccineConsumeItems $vaccineconsumeitem)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $animals = AnimalDetail::all();
        $vaccinations = Vaccine::all();
        $vaccination_items = PurchaseVaccineItems::with(['purchase_vaccine','vaccine','vaccine_consume_items'])->get();

         // Get the currently authenticated user
        $user = auth()->user();

        $usedAppointmentIds = VaccineConsumeDetails::pluck('appointment_id');//this line only get the appointment_id's in VaccineConsumeDetails

        $appointmentsQuery = Appointment::whereIn('id', $usedAppointmentIds);//this 

         // If user is not admin (role_id != 1), filter by veterinarian
        if ($user->role_id != 1) 
        {
        $appointmentsQuery->where('veterinarian_id', $user->id);
        }
    
        // Get the filtered appointments
        $appointments = $appointmentsQuery->get();


        return view('vaccine_consumption_details.edit',['animals'=>$animals,'vaccinations'=>$vaccinations,'vaccination_items'=>$vaccination_items,'vaccineconsumeitem'=>$vaccineconsumeitem,'appointments'=>$appointments]);
    }

    public function update(VaccineConsumeItems $vaccineconsumeitem,Request $request)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $data=$request->validate([
            'vaccination_date'=>'required',
            'appointment_id'=>'required|exists:appointments,id',
            
            'animal_id'=>'required|exists:animal_details,id',
            'vaccine_id'=>'required|exists:vaccines,id',
            'vaccination_item_id'=>'required|exists:purchase_vaccine_items,id',
            'consumed_quantity'=>'required|numeric|min:1'
        ]);

        $availableStock=$vaccineconsumeitem->purchase_vaccine_items->stock_quantity + $vaccineconsumeitem->consumed_quantity;

        if ($request->consumed_quantity > $availableStock) 
            {
                $errors["consumed_quantity"] = "The entered quantity exceedsss the available stock quantity.";
                return redirect()->back()->withErrors($errors)->withInput();
            }

            // Update consumed details
            $feedConsumeDetail=$vaccineconsumeitem->vaccine_consume_detail->update([
                   'appointment_id'=>$request->appointment_id,
                   'vaccination_date' => $request->vaccination_date,
                  
           ]);

            // Calculate new stock quantity
            $newStockQuantity = $availableStock - $request->consumed_quantity;

            // Update stock quantity
            $vaccineconsumeitem->purchase_vaccine_items->update([
                'stock_quantity' => $newStockQuantity
        ]);

        // Update the consumed record
        $vaccineconsumeitem->update($data);

        return redirect()->route('vaccine_consume_items.list')->with('success', 'Consumed Vaccine Item Record updated successfully.');
    }

    public function destroy(VaccineConsumeItems $vaccineconsumeitem)
    {
        
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 
        
        $vaccineconsumeitem->delete();

        return redirect()->route('vaccine_consume_items.list');
    }
}
