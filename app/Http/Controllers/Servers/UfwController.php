<?php

namespace App\Http\Controllers\Servers;

use Log;
use App\Server;
use App\FirewallRule;
use App\Http\Controllers\Controller;
use App\Jobs\Servers\AddFirewallRule;
use App\Http\Resources\ServerResource;
use App\Jobs\Servers\DeleteFirewallRule;
use App\Http\Requests\Servers\AddFirewallRuleRequest;

class UfwController extends Controller
{
    public function store(Server $server, AddFirewallRuleRequest $request)
    {
        $rule = $server->firewallRules()->create([
            'name' => $request->name,
            'port' => $request->port,
            'status' => STATUS_INSTALLING,
            'from' => implode(',', $request->from)
        ]);

        AddFirewallRule::dispatch($server, $rule);

        return new ServerResource($server);
    }

    public function destroy(Server $server, $rule)
    {
        $firewallRule = FirewallRule::findOrFail($rule);

        if ($firewallRule->status === STATUS_DELETING) {
            return new ServerResource($server);
        }

        $firewallRule->update([
            'status' => STATUS_DELETING
        ]);

        DeleteFirewallRule::dispatch($server, $firewallRule);

        return new ServerResource($server);
    }
}
