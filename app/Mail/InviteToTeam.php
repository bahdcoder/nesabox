<?php

namespace App\Mail;

use App\TeamInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteToTeam extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invite;

    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TeamInvite $invite)
    {
        $this->invite = $invite;
        $this->url = config('app.client_url') . '/account/teams';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('teams.invite');
    }
}
