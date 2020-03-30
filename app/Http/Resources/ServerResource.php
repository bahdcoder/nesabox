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
        $deploy_script_route =
            config('app.url') .
            route(
                'servers.custom-deploy-script',
                [$this->id, 'api_token' => $this->resource->user->api_token],
                false
            );

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
            'is_ready' => $this->status === STATUS_ACTIVE,
            'jobs' => JobResource::collection($this->jobs),
            'private_ip_address' => $this->private_ip_address,
            'database_instances' => $this->databaseInstances()
                ->with('databaseUsers')
                ->get(),
            'database_users_instances' => $this->databaseUsers()
                ->with('databases')
                ->get(),
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
                    'type',
                    'repository_provider'
                ])
                ->get()
                ->map(function ($site) {
                    return [
                        'id' => $site->id,
                        'name' => $site->name,
                        'type' => $site->type,
                        'status' => $site->status,
                        'repository' => $site->repository,
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
                ->select(['id', 'name'])
                ->get(),
            'friend_servers' => $this->friendServers()->pluck('friend_server_id')->all()
        ];
    }
}
