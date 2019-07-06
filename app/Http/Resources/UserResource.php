<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;

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
            'photo_url' => Gravatar::src($this->email),
            'access_token' => $this->createToken('Personal')->accessToken
        ];
    }
}
