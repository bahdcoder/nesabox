<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamRequest;

class TeamController extends Controller
{
    /**
     *
     * A user can create teams
     */
    public function store(CreateTeamRequest $request)
    {
        return response()->json(
            auth()
                ->user()
                ->teams()
                ->create($request->all())
        );
    }
}
