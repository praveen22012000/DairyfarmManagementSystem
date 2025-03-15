<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FeedVaccineDetails;
use App\Models\Vaccine;
use App\Models\Supplier;
use App\Models\SupplierFeed;
use App\Models\SupplierVaccine;

class SupplierController extends Controller
{
    //
    public function index()
    {
        $suppliers=Suppliers::all();

        return view('supplier_feed_vaccine_details.index',['suppliers'=>$suppliers]);
    }
    public function create()
    {
        $feeds=FeedVaccineDetails::all();
        $vaccines=Vaccine::all();

        
        return view('supplier_feed_vaccine_details.create',['feeds'=>$feeds,'vaccines'=>$vaccines]);
    }

    public function store(Request $request)
    {
          // Validate the request data
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'phone_no' => 'required|string|max:255',
        'email'=>'required|email',
        'address'=>'required',

        'feeds' => 'nullable|array|required_without:vaccines', // Ensure feeds is an array (if provided)
        'feeds.*' => 'exists:feed_vaccine_details,id', // Ensure each feed ID exists in the feeds table
        'vaccines' => 'nullable|array|required_without:feeds', // Ensure vaccines is an array (if provided)
        'vaccines.*' => 'exists:vaccines,id', // Ensure each vaccine ID exists in the vaccines table
    ]);



    // Create the supplier
    $supplier = Supplier::create([
        'name' => $data['name'],
        'phone_no' => $data['phone_no'],
        'email'=> $data['email'],
        'address'=>$data['address']
    ]);

    $received_feeds=$request->feeds;
    $received_vaccines=$request->vaccines;


     // Ensure $received_feeds is an array (use null coalescing to default to an empty array)
     $received_feeds = $request->feeds ?? [];

    foreach($received_feeds as $index => $received_feed_id)
    {
        SupplierFeed::create([

            'feed_id'=>$received_feed_id,
            'supplier_id'=>$supplier->id

        ]);

    }

    // Ensure $received_vaccines is an array (use null coalescing to default to an empty array)
    $received_vaccines = $request->vaccines ?? [];

    foreach($received_vaccines as $index => $received_vaccine_id)
    {
        SupplierVaccine::create([

            'vaccine_id'=>$received_vaccine_id,
            'supplier_id'=>$supplier->id

        ]);

    }

   

   // return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');



    }
}

