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
        $isReadyStatus = [
            'None' => false,
            'git' => $this->resource->repository_status === STATUS_ACTIVE,
            'ghost' => $this->resource->installing_ghost_status === STATUS_ACTIVE
        ];

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'repository' => $this->resource->repository,
            'app_type' => $this->resource->app_type ?? 'None',
            'is_ready' => $this->resource->status === STATUS_ACTIVE,
            'repository_branch' => $this->resource->repository_branch,
            'nesabox_domain' => $this->resource->getNexaboxSiteDomain(),
            'is_app_ready' => $isReadyStatus[$this->resource->app_type ?? 'None'],
            'updating_slug' => $this->resource->updating_slug_status === STATUS_UPDATING,
            'installing_repository' => $this->resource->repository_status === STATUS_INSTALLING,
            'installing_ghost' =>
                $this->resource->installing_ghost_status === STATUS_INSTALLING,
            'uninstalling_ghost' => $this->resource->installing_ghost_status === STATUS_UNINSTALLING
        ];
    }
}
