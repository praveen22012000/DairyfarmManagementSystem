<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseItemsController extends Controller
{
    //
    public function create()
    {
        return view('purchase_feed_vaccine.create');
    }
}
