<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    protected $includeServer = true;

    public function __construct($resource, $includeServer = false)
    {
        parent::__construct($resource);

        $this->includeServer = $includeServer;
    }
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
            // 'logs' => $this->resource->logs,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'type' => $this->resource->type,
            'repository' => $this->resource->repository,
            'deploying' => (bool) $this->resource->deploying,
            'app_type' => $this->resource->app_type ?? 'None',
            'push_to_deploy' => $this->resource->push_to_deploy,
            'is_ready' => $this->resource->status === STATUS_ACTIVE,
            'repository_branch' => $this->resource->repository_branch,
            'nesabox_domain' => $this->resource->getNexaboxSiteDomain(),
            'repository_provider' => $this->resource->repository_provider,
            'before_deploy_script' => $this->resource->before_deploy_script,
            'latest_deployment' => $this->resource->latestDeployment
                ? $this->resource->latestDeployment->properties
                : null,
            'is_app_ready' =>
                $isReadyStatus[$this->resource->app_type ?? 'None'],
            'updating_slug' =>
                $this->resource->updating_slug_status === STATUS_UPDATING,
            'deployment_trigger_url' =>
                config('app.url') .
                route(
                    'sites.trigger-deployment',
                    [
                        $this->resource->id,
                        'api_token' => $this->resource->server->user->api_token
                    ],
                    false
                ),
            'installing_repository' =>
                $this->resource->repository_status === STATUS_INSTALLING,
            'installing_certificate' =>
                $this->resource->installing_certificate_status ===
                STATUS_INSTALLING,
            'ssl_certificate_installed' =>
                $this->resource->installing_certificate_status ===
                STATUS_ACTIVE,
            $this->mergeWhen($this->includeServer, [
                'server' => new ServerResource($this->server)
            ]),
            'balanced_servers' => $this->balancedServers()
                ->select(['balanced_server_id', 'port'])
                ->get()
        ];
    }
}
