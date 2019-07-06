<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'id' => $this->resource->id,
            'photo_url' => $this->photo_url,
            'providers' => [
                'digital-ocean' => collect($this->providers['digital-ocean'])->map(function ($credential) {
                    return [
                        'id' => $credential['id'],
                        'profileName' => $credential['profileName']
                    ];
                }),
                'vultr' => collect($this->providers['vultr'])->map(function ($credential) {
                    return [
                        'id' => $credential['id'],
                        'profileName' => $credential['profileName']
                    ];
                }),
                'aws' => collect($this->providers['aws'])->map(function ($credential) {
                    return [
                        'id' => $credential['id'],
                        'profileName' => $credential['profileName']
                    ];
                })
            ],
            'access_token' => $this->createToken('Personal')->accessToken,
        ];
    }
}
