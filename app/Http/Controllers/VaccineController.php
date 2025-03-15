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


    }
}
