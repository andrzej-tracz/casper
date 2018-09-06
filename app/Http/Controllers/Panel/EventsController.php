<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * Show the list of all user events
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('panel.events.index');
    }
}
