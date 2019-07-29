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
            'is_ready' => $this->status === STATUS_ACTIVE,
            'jobs' => JobResource::collection($this->jobs),
            'daemons' => DaemonsResource::collection($this->daemons),
            'mysql_database_users' => DatabaseUserResource::collection(
                $this->mysqlDatabaseUsers
            ),
            'mongodb_database_users' => DatabaseUserResource::collection(
                $this->mongoDbDatabaseUsers
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
            'deploy_script' =>
                $this->provider === CUSTOM_PROVIDER
                    ? route('servers.custom-deploy-script', $this->id)
                    : null
        ];
    }
}
