<?php

namespace App\Jobs\Servers;

use App\Server;
use App\FirewallRule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\AddFirewallRule as AppAddFirewallRule;

class AddFirewallRule implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        BroadcastServer;

    public $server;

    public $rule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, FirewallRule $rule)
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
        $process = (new AppAddFirewallRule($this->server, $this->rule))->run();

        if ($process->isSuccessful()) {
            $this->rule->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->broadcastServerUpdated();
        } else {
            $message = "Failed to add firewall rule {$this->rule->name} on server {$this->server->name}.";

            $this->rule->delete();

            $this->broadcastServerUpdated();

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
