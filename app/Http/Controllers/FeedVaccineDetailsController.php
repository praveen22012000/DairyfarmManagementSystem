<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeedVaccineDetails;

class FeedVaccineDetailsController extends Controller
{
    //

    public function create()
    {
        return view('feed_and_vaccine.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'manufacturer'=>'required',
            'type'=>'required',
            'unit_type'=>'required',
            'unit_price'=>'required'
        ]);

        FeedVaccineDetails::create([
            'name'=>$request->name,
            'manufacturer'=>$request->manufacturer,
            'type'=>$request->type,
            'unit_type'=>$request->unit_type,
            'unit_price'=>$request->unit_price
        ]);


    }
}
