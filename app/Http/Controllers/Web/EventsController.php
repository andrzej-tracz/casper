<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    public function nearest()
    {
        return view('web.events.nearest');
    }
}
