<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            'app_type' => $this->resource->app_type ?? 'None',
            'is_ready' => $this->resource->status === STATUS_ACTIVE,
            'nesabox_domain' => $this->resource->getNexaboxSiteDomain(),
            'installing_ghost' =>
                $this->resource->installing_ghost_status === STATUS_INSTALLING,
            'uninstalling_ghost' => $this->resource->installing_ghost_status === STATUS_UNINSTALLING
        ];
    }
}
