<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DaemonsResource extends JsonResource
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
            'slug' => $this->resource->slug,
            'user' => $this->resource->user,
            'status' => $this->resource->status,
            'command' => $this->resource->command,
            'processes' => $this->resource->processes,
            'isReady' => $this->resource->status === STATUS_ACTIVE
        ];
    }
}
