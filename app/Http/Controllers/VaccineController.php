<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use Illuminate\Support\Facades\Auth;

class VaccineController extends Controller
{
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $vaccines=Vaccine::all();

        return view('vaccine_details.index',['vaccines'=>$vaccines]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        return view('vaccine_details.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $request->validate([
            'vaccine_name'=>'required|unique:vaccines,vaccine_name',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required|numeric|min:1'
        ]);

        Vaccine::create([
            'vaccine_name'=>$request->vaccine_name,
            'manufacturer'=>$request->manufacturer,
        
            'unit_type'=>$request->unit_type,
            'unit_price'=>$request->unit_price
        ]);

        return redirect()->route('vaccine.list')->with('success', 'Vaccine record created successfully!');
    }

    public function edit(Vaccine $vaccine)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        return view('vaccine_details.edit',['vaccine'=>$vaccine]);
    }

    public function update(Request $request,Vaccine $vaccine)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $data=$request->validate([
            'vaccine_name'=>'required|unique:vaccines,vaccine_name',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required|numeric|min:1'
        ]);

        $vaccine->update($data);


        return redirect()->route('vaccine.list')->with('success', 'Vaccine record updated successfully!');

    }

    public function destroy(Vaccine $vaccine)
    {
        if (!in_array(Auth::user()->role_id, [1, 6, 2])) 
        {
            abort(403, 'Unauthorized action.');
        } 

        $vaccine->delete();

        return redirect()->route('vaccine.list');

    }
}
