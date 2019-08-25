<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FirewallRuleResource extends JsonResource
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
            'port' => $this->resource->port,
            'from' => $this->resource->from,
        ];
    }
}
