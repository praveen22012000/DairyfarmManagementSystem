<?php

namespace App\Http\Controllers;
use App\Models\MilkProduct;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;

class ManufacturerProductController extends Controller
{
    //
    public function create()
    {

        $milkProducts=MilkProduct::all();


        $farm_labor_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labor_id)->get();

     

        return view('manufacturer_products.create',['milkProducts'=>$milkProducts,'farm_labors'=>$farm_labors]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'date'=>['required','date','before_or_equal:today'],
            'time'=>'required',
            'enter_by'=>'required',
            'quantity'=>'required|array',
            'quantity.*'=>'required|numeric|min:1',
            'manufacture_date'=>['required','date','before_or_equal:today'],
            'expire_date'=>'required',
            'user_id'=>'required|exists:users,id'

        ]);

        $manufacturers=Manufacturer::create([
            'date'=>$request->date,
            'time'=>$request->time,
            'enter_by'=>$request->enter_by
        ]);

        ManufacturerProduct::create([
            'manufacturer_id'=>$manufacturers->id,
            ''
        ]);


    }
}
