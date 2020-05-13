<?php

namespace App\Http\Resources;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     *
     * Return visible properties of credentials
     */
    public function defineCredential($credential)
    {
        return [
            'id' => $credential['id'],
            'profileName' => $credential['profileName']
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $token = request()->bearerToken();
        $serverCount = $this->servers->count();

        return [
            'name' => $this->name,
            'email' => $this->email,
            'id' => $this->resource->id,
            // 'api_token' => $this->api_token,
            'photo_url' => $this->photo_url,
            'auth_provider' => $this->auth_provider,
            'server_count' => $serverCount,
            'sshkeys' => SshkeyResource::collection($this->sshkeys),
            'source_control' => [
                'github' => (bool) $this->source_control['github'],
                'gitlab' => (bool) $this->source_control['gitlab']
                // 'bitbucket' => (bool) $this->source_control['bitbucket'] TODO: Fix Bitbucket connection
            ],
            'providers' => [
                DIGITAL_OCEAN => collect($this->providers[DIGITAL_OCEAN])->map(
                    function ($credential) {
                        return $this->defineCredential($credential);
                    }
                ),
                VULTR => collect($this->providers[VULTR])->map(function (
                    $credential
                ) {
                    return $this->defineCredential($credential);
                }),
                LINODE => collect($this->providers[LINODE])->map(function (
                    $credential
                ) {
                    return $this->defineCredential($credential);
                })
            ],
            // 'access_token' => JWTAuth::fromUser($this->resource, [
            //     'exp' => \Carbon\Carbon::now()->addDays(7)->timestamp
            // ]),
            'subscription' => [
                'status' => (bool) $this->subscription
                    ? $this->subscription->status
                    : null,
                'id' => (bool) $this->subscription
                    ? $this->subscription->id
                    : null,
                'plan' => $this->getCurrentPlanName()
            ],
            'can_create_more_servers' => $this->getCurrentPlanName() === 'free' ? $serverCount < 1 : true
        ];
    }
}
