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
            'is_ready' => (bool) $this->is_ready,
            'node_version' => $this->node_version,
            'ssh_keys' => SshkeyResource::collection($this->personalSshkeys)
        ];
    }
}
