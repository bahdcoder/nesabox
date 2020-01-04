<?php

namespace App\Http\Controllers\Users;

use App\Events\UserInvitedToTeam;
use App\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamInviteRequest;
use App\TeamInvite;
use App\User;

class TeamInvitesController extends Controller
{
    public function store(CreateTeamInviteRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        $user = User::where('email', $request->email)->first();

        $invite = $team->invites()->create([
            'user_id' => $user !== null ? $user->id : null,
            'email' => $request->email
        ]);

        event(new UserInvitedToTeam($invite));

        return response()->json($invite);
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
                'status' => $status
            ]);
        } else {
            $teamInvite->delete();
        }

        // TODO: Notify owner of team the the invite was accepted or rejected.

        return response()->json([]);
    }
}
