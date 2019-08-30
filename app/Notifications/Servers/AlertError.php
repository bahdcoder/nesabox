<?php

namespace App\Notifications\Servers;

use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AlertError extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The server to alert
     * 
     * @var \App\Server
     */
    public $server;

    /**
     * The alert message
     * 
     * @var string
     */
    public $message;

    /**
     * The output from command.
     * 
     * @var string
     */
    public $output;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Server $server, string $message, string $output = null)
    {
        $this->output = $output;
        $this->server = $server;
        $this->message = $message;

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
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'output' => $this->output
        ];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'data' => [
                'message' => $this->message,
                'output' => $this->output
            ]
        ]))
            ->onConnection('redis')
            ->onQueue('broadcasts');
    }
}