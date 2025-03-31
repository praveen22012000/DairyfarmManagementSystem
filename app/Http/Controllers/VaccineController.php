<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;

class VaccineController extends Controller
{
    //

    public function index()
    {
        $vaccines=Vaccine::all();

        return view('vaccine_details.index',['vaccines'=>$vaccines]);
    }

    public function create()
    {
        return view('vaccine_details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vaccine_name'=>'required',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required'
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
        return view('vaccine_details.edit',['vaccine'=>$vaccine]);
    }

    public function update(Request $request,Vaccine $vaccine)
    {
        $data=$request->validate([
            'vaccine_name'=>'required',
            'manufacturer'=>'required',
          
            'unit_type'=>'required',
            'unit_price'=>'required'
        ]);

        $vaccine->update($data);


        return redirect()->route('vaccine.list')->with('success', 'Vaccine record updated successfully!');

    }

    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();

        return redirect()->route('vaccine.list');

    }
}
