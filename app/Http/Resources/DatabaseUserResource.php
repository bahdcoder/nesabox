<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseUserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            $this->mergeWhen($this->type === MONGO_DB, [
                'readonly' => (bool) $this->read_only
            ]),
            'is_ready' => $this->status === STATUS_ACTIVE
        ];
    }
}
