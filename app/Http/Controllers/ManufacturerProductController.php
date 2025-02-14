<?php

namespace App\Http\Controllers;
use App\Models\MilkProduct;
use Illuminate\Http\Request;

class ManufacturerProductController extends Controller
{
    //
    public function create()
    {

        $milkProducts=MilkProduct::all();

        return view('manufacturer_products.create',['milkProducts'=>$milkProducts]);
    }
}
