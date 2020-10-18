<?php

namespace App\Listeners;

use App\Mail\InviteToTeam;
use Illuminate\Support\Facades\Mail;

class SendTeamInviteMail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->invite->email)->send(new InviteToTeam($event->invite));
    }
}
