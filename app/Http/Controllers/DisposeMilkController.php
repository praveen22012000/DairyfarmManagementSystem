<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\ProductionMilk;

class DisposeMilkController extends Controller
{
    //
    public function create()
    {
        $ProductionsMilk = ProductionMilk::where('stock_quantity', '>', 0)->get();

        $farm_labore_id=Role::where('role_name','FarmLabore')->pluck('id');

        $farm_labors=User::where('role_id',$farm_labore_id)->get();

        return view('dispose_milk.create',['ProductionsMilk'=>$ProductionsMilk,'farm_labors'=>$farm_labors]);


    }
}
