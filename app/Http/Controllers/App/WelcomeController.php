<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            return redirect('/dashboard');
        }
        return view('app.welcome');
    }
}
