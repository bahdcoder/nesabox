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
            'region' => $this->region,
            'provider' => $this->provider,
            'databases' => $this->databases,
            'ip_address' => $this->ip_address,
            'node_version' => $this->node_version,
            'is_ready' => $this->status === STATUS_ACTIVE,
            'ssh_keys' => SshkeyResource::collection($this->personalSshkeys),
            $this->mergeWhen(
                (bool) request()->query('with_databases') === true,
                [
                    'database_users' => DatabaseUserResource::collection(
                        $this->databaseUsers
                    )
                ]
            )
        ];
    }
}
