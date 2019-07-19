<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;

class GetServersController extends Controller
{
    /**
     * Fetch all servers of authenticated user
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        return ServerResource::collection(auth()->user()->servers);
    }
}
