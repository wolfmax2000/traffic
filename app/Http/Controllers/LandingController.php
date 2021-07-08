<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends FrontController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        die();
        return view('home');
    }
}
