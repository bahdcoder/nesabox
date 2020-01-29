<?php

namespace App\Notifications\Servers;

use App\Server;
use Illuminate\Bus\Queueable;
use Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ServerProvisioned extends Notification implements ShouldQueue
{
    use Queueable;

    public $server;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;

        $this->onQueue('mails');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Server provisioned : ' . $this->server->name)
            ->markdown('mail.servers.provisioned', [
                'server' => $this->server,
                'url' =>
                    config('app.client_url') . '/servers/' . $this->server->id
            ]);
    }
}
