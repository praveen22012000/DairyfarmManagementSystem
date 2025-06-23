<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Retailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class RetailerController extends Controller
{
    //

    public function edit(Retailer $retailer)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();

        $retailers=Retailer::with('user')->get();


        return view('retailers.edit',['roles'=>$roles,'retailers'=>$retailers,'retailer'=>$retailer]);
    
    }

    public function update(Request $request,Retailer $retailer)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $data=$request->validate([

            'store_name'=>'required|string',
            'business_type'=>'required|string',
            'tax_id'=>"required|unique:retailers,tax_id,$retailer->id"
        ]);

        $retailer->update($data);

        return redirect()->route('retailers.list')->with('success', 'retailer record updated successfully!');
    }

    public function view(Retailer $retailer)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
        $roles = Role::all(); // Fetch all roles

        $retailers=Retailer::with('user')->get();

        
        return view('retailers.view',['roles'=>$roles,'retailers'=>$retailers,'retailer'=>$retailer]);
    }

    public function destroy(Retailer $retailer)
    {
         if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $retailer->delete();
        
        return redirect()->route('retailers.list');
    }

}
