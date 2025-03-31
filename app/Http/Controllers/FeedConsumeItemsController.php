<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedConsumeItemsController extends Controller
{
    //

    public function create()
    {
        return view('feed_consumption.create');
    }
}
