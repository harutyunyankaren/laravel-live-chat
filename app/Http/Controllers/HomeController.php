<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $rooms = ChatRoom::all();
        } else {
            $rooms = ChatRoom::where('is_private', 0)->get();
        }

        return view('home', compact('rooms'));
    }
}
