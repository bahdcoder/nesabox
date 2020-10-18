<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SshkeyResource extends JsonResource
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
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'status' => $this->resource->status,
            'isReady' => $this->resource->status === STATUS_ACTIVE
        ];
    }
}
