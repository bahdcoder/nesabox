<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Show the authenticated user details
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        return new UserResource(auth()->user());
    }
}
