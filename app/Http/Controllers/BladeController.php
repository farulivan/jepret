<?php

namespace App\Http\Controllers;

class BladeController extends Controller
{
    public function showMainPage()
    {
        return view('main');
    }

    public function showLoginPage()
    {
        return view('login');
    }
}
