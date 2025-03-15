<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;

class FeedController extends Controller
{
    //
    //

    public function index()
    {
        $feedVaccineDetails=Feed::all();

        return view('feed_details.index',['feedVaccineDetails'=>$feedVaccineDetails]);
    }

    public function create()
    {
        return view('feed_details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'feed_name'=>'required',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required'
        ]);

        Feed::create([
            'feed_name'=>$request->feed_name,
            'manufacturer'=>$request->manufacturer,
        
            'unit_type'=>$request->unit_type,
            'unit_price'=>$request->unit_price
        ]);


    }
}
