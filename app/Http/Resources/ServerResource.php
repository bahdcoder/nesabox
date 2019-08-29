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
            'status' => $this->status,
            'region' => $this->region,
            'ssh_key' => $this->ssh_key,
            'provider' => $this->provider,
            'databases' => $this->databases,
            'ip_address' => $this->ip_address,
            'node_version' => $this->node_version,
            'log_watcher_site' => "https://{$this->resource->getLogWatcherSiteDomain()}",
            'server_monitoring_username' => $this->server_monitoring_username,
            'server_monitoring_password' => $this->server_monitoring_password,
            'server_monitoring_installed' =>
                $this->server_monitoring_status === STATUS_ACTIVE,
            'server_monitoring_site' =>
                'https://' .
                $this->resource->getNesaboxServerMonitoringDomain(),
            'server_monitoring_installing' =>
                $this->server_monitoring_status === STATUS_INSTALLING,
            'is_ready' => $this->status === STATUS_ACTIVE,
            'jobs' => JobResource::collection($this->jobs),
            'daemons' => DaemonsResource::collection($this->daemons),
            'mysql_database_users' => DatabaseUserResource::collection(
                $this->mysqlDatabaseUsers
            ),
            'mongodb_database_users' => DatabaseUserResource::collection(
                $this->mongoDbDatabaseUsers
            ),
            'firewall_rules' => FirewallRuleResource::collection(
                $this->resource->firewallRules
            ),
            'nesa_key' => $this->sshkeys()
                ->where('is_app_key', true)
                ->first()->key,
            'sites' => SiteResource::collection($this->sites),
            'sshkeys' => SshkeyResource::collection($this->personalSshkeys),
            'mongodb_databases' => DatabaseResource::collection(
                $this->mongodbDatabases
            ),
            'mysql_databases' => DatabaseResource::collection(
                $this->mysqlDatabases
            ),
            $this->mergeWhen($this->provider === CUSTOM_PROVIDER, [
                'deploy_script' => $deploy_script_route,
                'deploy_command' => "curl -Ss '{$deploy_script_route}' >/tmp/nesabox.sh && bash /tmp/nesabox.sh"
            ])
        ];
    }
}
