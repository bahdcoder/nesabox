<?php

namespace App\Notifications\Sites;

use App\Site;
use Illuminate\Bus\Queueable;
use App\Http\Resources\SiteResource;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SiteUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The updated site
     *
     * @var \App\Site
     */
    public $site;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;

        $this->onConnection('redis');

        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return (new BroadcastMessage([
            'site' => (new SiteResource($this->site))->resolve()
        ]))
            ->onConnection('redis')
            ->onQueue('broadcasts');
    }
}
