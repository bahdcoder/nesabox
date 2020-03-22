<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('app.welcome');
    }
}
