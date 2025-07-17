<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feed;
use App\Models\Vaccine;
use App\Models\Supplier;
use App\Models\SupplierFeed;
use App\Models\SupplierVaccine;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    //
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
    
        $suppliers=Supplier::all();

      
        return view('supplier_feed_vaccine_details.index',['suppliers'=>$suppliers]);
    }
    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $feeds=Feed::all();
        $vaccines=Vaccine::all();

        
        return view('supplier_feed_vaccine_details.create',['feeds'=>$feeds,'vaccines'=>$vaccines]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
          // Validate the request data
        $data = $request->validate([
        'name' => 'required|string|max:255',
    
        'phone_no' => ['required','regex:/^(07[0-9]\d{7})|(021\d{7})$/','unique:suppliers,phone_no'],
         'email' => ['required','email','unique:suppliers,email'],
        'address'=>'required'

    ]);



    // Create the supplier
    $supplier = Supplier::create([
        'name' => $data['name'],
        'phone_no' => $data['phone_no'],
        'email'=> $data['email'],
        'address'=>$data['address']
    ]);

 

    return redirect()->route('supplier_details.list')->with('success','supplier details saved successfully');



    }

    public function edit(Supplier $supplier)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        return view('supplier_feed_vaccine_details.edit',['supplier'=>$supplier]);
    }

    public function view(Supplier $supplier)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
         return view('supplier_feed_vaccine_details.view',['supplier'=>$supplier]);  
    }

    public function update(Supplier $supplier,Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $data=$request->validate([
            'name'=>'required',
            'phone_no'=>['required','numeric','regex:/^(07[0-9]\d{7})|(021\d{7})$/','unique:suppliers,phone_no,'.$supplier->id],     
            'email'=>"required|email|unique:suppliers,email,$supplier->id",
            'address'=>'required'
        ]);

        $supplier->update($data);

        return redirect()->route('supplier_details.list')->with('success', 'Supplier record updated successfully!');
        
    }

    public function destroy(Supplier $supplier)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $supplier->delete();

        return redirect()->route('supplier_details.list')->with('success', 'Supplier record deleted successfully!');
    }
}

