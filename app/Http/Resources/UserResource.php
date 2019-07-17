<?php

namespace App\Http\Resources;

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
        return [
            'name' => $this->name,
            'email' => $this->email,
            'id' => $this->resource->id,
            'photo_url' => $this->photo_url,
            'source_control' => [
                'github' => (bool) $this->source_control['github'],
                'gitlab' => (bool) $this->source_control['gitlab'],
                'bitbucket' => (bool) $this->source_control['bitbucket']
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
                AWS => collect($this->providers[AWS])->map(function (
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
            'access_token' => $this->createToken('Personal')->accessToken
        ];
    }
}
