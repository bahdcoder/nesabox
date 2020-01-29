<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $deploy_script_route = route('servers.custom-deploy-script', [
            $this->id,
            'api_token' => $this->resource->user->api_token
        ]);

        return [
            'id' => $this->id,
            'size' => $this->size,
            'slug' => $this->slug,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'region' => $this->region,
            'ssh_key' => $this->ssh_key,
            'user_id' => $this->user_id,
            'provider' => $this->provider,
            'databases' => $this->databases,
            'ip_address' => $this->ip_address,
            'node_version' => $this->node_version,
            'is_ready' => $this->status === STATUS_ACTIVE,
            'jobs' => JobResource::collection($this->jobs),
            'daemons' => DaemonsResource::collection($this->daemons),
            'firewall_rules' => FirewallRuleResource::collection(
                $this->resource->firewallRules
            ),
            'nesa_key' => $this->sshkeys()
                ->where('is_app_key', true)
                ->first()->key,
            'sites' => $this->resource
                ->sites()
                ->select([
                    'id',
                    'name',
                    'app_type',
                    'status',
                    'repository_provider'
                ])
                ->get()
                ->map(function ($site) {
                    return [
                        'id' => $site->id,
                        'name' => $site->name,
                        'status' => $site->status,
                        'app_type' => $site->app_type ?? 'None',
                        'repository_provider' => $site->repository_provider
                    ];
                }),
            'sshkeys' => SshkeyResource::collection($this->personalSshkeys),
            $this->mergeWhen($this->provider === CUSTOM_PROVIDER, [
                'deploy_script' => $deploy_script_route,
                'deploy_command' => "curl -Ss '{$deploy_script_route}' >/tmp/nesabox.sh && bash /tmp/nesabox.sh"
            ]),
            'family_servers' => $this->user
                ->servers()
                ->where('id', '!=', $this->id)
                ->where('region', $this->region)
                ->where('provider', $this->provider)
                ->whereNotNull('private_ip_address')
                ->get(),
            'balanced_servers' => $this->balancedServers,
            'friend_servers' => $this->friendServers
        ];
    }
}
