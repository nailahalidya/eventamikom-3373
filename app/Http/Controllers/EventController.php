<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('event-detail');
    }

    //Menampilkan halaman checkout
    public function show()
    {
        return view('checkout');
    }
}
