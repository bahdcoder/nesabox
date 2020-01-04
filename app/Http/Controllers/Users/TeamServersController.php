<?php

namespace App\Http\Controllers\Users;

use App\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\UpdateTeamServersRequest;
use App\Http\Resources\TeamResource;

class TeamServersController extends Controller
{
    public function index(Team $team)
    {
        $this->authorize('view', $team);

        return new TeamResource($team);
    }

    public function update(UpdateTeamServersRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        foreach ($request->servers as $serverId):
            auth()
                ->user()
                ->servers()
                ->where('id', $serverId)
                ->firstOrFail();
        endforeach;

        $team->servers()->sync($request->servers);

        return new TeamResource($team->fresh());
    }
}
