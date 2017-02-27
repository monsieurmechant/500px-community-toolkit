<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowersController extends Controller
{

    public function index()
    {
        return view('followers.index');
   } 
}
