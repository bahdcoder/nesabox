<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Team;
use App\TeamInvite;

class TeamController extends Controller
{
    /**
     *
     * Fetch all teams of authenticated users
     */
    public function index()
    {
        return response()->json(
            TeamResource::collection(auth()->user()->teams)
        );
    }

    /**
     *
     * This fetches all the team memberships for a user
     * The user is a part of a team if the
     * status of the invite/membership
     * is accepted.
     */
    public function memberships()
    {
        return response()->json(
            auth()
                ->user()
                ->memberships()
                ->paginate(25)
        );
    }

    /**
     *
     * A user can create teams
     */
    public function store(CreateTeamRequest $request)
    {
        auth()
            ->user()
            ->teams()
            ->create($request->only(['name']));

        return new UserResource(auth()->user());
    }

    /**
     *
     * A user can update a team
     */
    public function update(CreateTeamRequest $request, Team $team)
    {
        $this->authorize('view', $team);

        $team->update($request->only(['name']));

        return new UserResource(auth()->user());
    }

    /**
     *
     * A user can delete a team
     */
    public function destroy(Team $team)
    {
        $this->authorize('view', $team);

        $team->invites()->delete();

        $team->servers()->sync([]);

        $team->delete();

        return new UserResource(auth()->user());
    }
}
