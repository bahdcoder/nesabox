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
            'daemons' => DaemonsResource::collection($this->daemons),
            'nesa_key' => $this->sshkeys()
                ->where('is_app_key', true)
                ->first()->key,
            'sshkeys' => SshkeyResource::collection($this->personalSshkeys),
            $this->mergeWhen(
                (bool) request()->query('with_databases') === true,
                [
                    'database_users' => DatabaseUserResource::collection(
                        $this->databaseUsers
                    )
                ]
            ),
            'deploy_script' =>
                $this->provider === CUSTOM_PROVIDER
                    ? route('servers.custom-deploy-script', $this->id)
                    : null
        ];
    }
}
