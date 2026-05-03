<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $oRequest)
    {
        $this->middleware('guest');
        return view('auth.user_login');
    }

    public function registration(Request $oRequest)
    {
        $this->middleware('guest');
        return view('auth.user_registration');
    }
}
