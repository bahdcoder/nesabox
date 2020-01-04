<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;

class GetServersController extends Controller
{
    /**
     * Fetch all servers of authenticated user
     *
     * Also fetch all servers shared with this user.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        return [
            'servers' => ServerResource::collection(auth()->user()->servers),
            'team_servers' => auth()
                ->user()
                ->acceptedMemberships()
                ->get()
        ];
    }
}
