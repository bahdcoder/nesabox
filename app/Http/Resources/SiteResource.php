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
            'pm2_processes' => collect($this->resource->pm2Processes)->map(
                function ($process, $index) {
                    return [
                        'id' => $process->id,
                        'label' => $process->name,
                        'value' => $process->logs_path,
                        'deletable' => $index !== 0
                    ];
                }
            ),
            'app_type' => $this->resource->app_type ?? 'None',
            'is_ready' => $this->resource->status === STATUS_ACTIVE,
            'repository_branch' => $this->resource->repository_branch,
            'nesabox_domain' => $this->resource->getNexaboxSiteDomain(),
            'after_deploy_script' => $this->resource->after_deploy_script,
            'repository_provider' => $this->resource->repository_provider,
            'before_deploy_script' => $this->resource->before_deploy_script,
            'deployments' => Activity::forSubject($this->resource)
                ->where('description', 'Deployment')
                ->latest()
                ->paginate(),
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
                $this->resource->installing_ghost_status === STATUS_UNINSTALLING
        ];
    }
}
