<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Activity;

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
            'ghost' =>
                $this->resource->installing_ghost_status === STATUS_ACTIVE
        ];

        return [
            'id' => $this->resource->id,
            'logs' => $this->resource->logs,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'repository' => $this->resource->repository,
            'deploying' => (bool) $this->resource->deploying,
            'app_type' => $this->resource->app_type ?? 'None',
            'push_to_deploy' => $this->resource->push_to_deploy,
            'is_ready' => $this->resource->status === STATUS_ACTIVE,
            'repository_branch' => $this->resource->repository_branch,
            'nesabox_domain' => $this->resource->getNexaboxSiteDomain(),
            'repository_provider' => $this->resource->repository_provider,
            'before_deploy_script' => $this->resource->before_deploy_script,
            'deployments' => $this->resource->deployments,
            'is_app_ready' =>
                $isReadyStatus[$this->resource->app_type ?? 'None'],
            'updating_slug' =>
                $this->resource->updating_slug_status === STATUS_UPDATING,
            'deployment_trigger_url' => route('sites.trigger-deployment', [
                $this->resource->id,
                'api_token' => $this->resource->server->user->api_token
            ]),
            'installing_repository' =>
                $this->resource->repository_status === STATUS_INSTALLING,
            'installing_ghost' =>
                $this->resource->installing_ghost_status === STATUS_INSTALLING,
            'uninstalling_ghost' =>
                $this->resource->installing_ghost_status ===
                STATUS_UNINSTALLING,
            'installing_certificate' =>
                $this->resource->installing_certificate_status ===
                STATUS_INSTALLING,
            'ssl_certificate_installed' =>
                $this->resource->installing_certificate_status ===
                STATUS_ACTIVE,
            'server' => new ServerResource($this->server)
        ];
    }
}
