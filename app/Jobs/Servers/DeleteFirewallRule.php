<?php

namespace App\Jobs\Servers;

use App\Server;
use App\FirewallRule;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\DeleteFirewallRule as AppDeleteFirewallRule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteFirewallRule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $server;

    public $rule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Firewallrule $rule)
    {
        $this->rule = $rule;
        $this->server = $server;

        $this->onQueue('firewall_rules');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteFirewallRule(
            $this->server,
            $this->rule
        ))->run();

        if ($process->isSuccessful()) {
            $this->rule->delete();
        } else {
            // TODO: alert the user
            $this->rule->update([
                'status' => STATUS_ACTIVE
            ]);

            $message = "Failed deleting firewall rule {$this->rule->name} on server {$this->server->name}.";

            $this->rule->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->broadcastServerUpdated();

            $this->alertServer($message, $process->getErrorOutput());
        }

        Notification::send(
            $this->server->getAllMembers(),
            new ServerIsReady($this->server)
        );
    }
}
