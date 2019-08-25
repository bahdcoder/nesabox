<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Jobs\Servers\AddFirewallRule;
use App\Http\Requests\Servers\AddFirewallRuleRequest;
use App\Http\Resources\ServerResource;

class UfwController extends Controller
{
    public function store(Server $server, AddFirewallRuleRequest $request)
    {
        $rule = $server->firewallRules()->create([
            'name' => $request->name,
            'port' => $request->port,
            'status' => STATUS_INSTALLING,
            'from' => (bool) $request->from ? implode(',', $request->from) : 'Any'
        ]);

        AddFirewallRule::dispatch($server, $rule);

        return new ServerResource($server);
    }
}
