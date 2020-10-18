<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseResource extends JsonResource
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
            // 'user' => $this->databaseUser->name,
            $this->mergeWhen($this->type === MONGO_DB, [
                'database_users' => DatabaseUserResource::collection(
                    $this->databaseUsers()
                        ->where('status', '!=', STATUS_DELETING)
                        ->get()
                )
            ]),
            'is_ready' => $this->status === STATUS_ACTIVE
        ];
    }
}
