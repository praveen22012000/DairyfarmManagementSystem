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
        $feedDetails=Feed::all();

        return view('feed_details.index',['feedDetails'=>$feedDetails]);
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

        return redirect()->route('feed_vaccine.list')->with('success', 'Feed record created successfully!');
    }

    public function view(Feed $feed)
    {
        return view('feed_details.view',['feed'=>$feed]);
    }

    public function edit(Feed $feed)
    {
        return view('feed_details.edit',['feed'=>$feed]);
    }

    public function update(Request $request,Feed $feed)
    {
        $data=$request->validate([
            'feed_name'=>'required',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required'
        ]);

        $feed->update($data);

        return redirect()->route('feed_vaccine.list')->with('success', 'Feed record updated successfully!');

    }

    public function destroy(Feed $feed)
    {
        $feed->delete();

        return redirect()->route('feed_vaccine.list');
    }


}
