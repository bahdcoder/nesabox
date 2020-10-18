<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class JobResource extends JsonResource
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
            'status' => $this->status,
            'slug' => $this->resource->slug,
            'user' => $this->resource->user,
            'cron' => $this->resource->cron,
            'command' => $this->resource->command,
            'isReady' => $this->status === STATUS_ACTIVE,
            'frequency' => __('app.' . $this->resource->frequency)
        ];
    }
}
