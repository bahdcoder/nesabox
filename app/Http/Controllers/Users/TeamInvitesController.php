<?php

namespace App\Http\Controllers\Users;

use App\Events\UserInvitedToTeam;
use App\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamInviteRequest;
use App\Http\Resources\TeamResource;
use App\TeamInvite;
use App\User;

class TeamInvitesController extends Controller
{
    public function index()
    {
        return auth()
            ->user()
            ->invites()
            ->with(['team.user'])
            ->get();
    }

    public function store(CreateTeamInviteRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        $user = User::where('email', $request->email)->first();

        $data = [
            'email' => $request->email,
            'status' => 'pending'
        ];

        if ($user) {
            $data['user_id'] = $user->id;

            if ((int) $user->id === (int) auth()->user()->id) {
                abort(400, 'You cannot invite yourself to a team.');
            }
        }

        $invite = $team->invites()->create($data);

        event(new UserInvitedToTeam($invite));

        return new TeamResource($team->fresh());
    }

    public function show(TeamInvite $teamInvite)
    {
        return response()->json([
            'id' => $teamInvite->id,
            'team' => [
                'name' => $teamInvite->team->name
            ]
        ]);
    }

    public function update(TeamInvite $teamInvite, $status)
    {
        $this->authorize('update', $teamInvite);

        if (!in_array($status, ['accepted', 'declined'])) {
            abort(400, 'The status must be accepted or declined.');
        }

        if ($status === 'accepted') {
            $teamInvite->update([
                'status' => 'active'
            ]);
        } else {
            $teamInvite->delete();
        }

        // TODO: Notify owner of team the the invite was accepted or rejected.

        return response()->json([]);
    }

    public function destroy(TeamInvite $teamInvite)
    {
        $team = $teamInvite->team;
        $this->authorize('destroy', $team);

        $teamInvite->delete();

        return new TeamResource($team->fresh());
    }
}
