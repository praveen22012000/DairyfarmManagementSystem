<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    //
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $feedDetails=Feed::all();

        return view('feed_details.index',['feedDetails'=>$feedDetails]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        return view('feed_details.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'feed_name'=>'required|unique:feeds,feed_name',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required|numeric|min:0.01'
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
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        return view('feed_details.view',['feed'=>$feed]);
    }

    public function edit(Feed $feed)
    {
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        return view('feed_details.edit',['feed'=>$feed]);
    }

    public function update(Request $request,Feed $feed)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

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
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $feed->delete();

        return redirect()->route('feed_vaccine.list');
    }


}
