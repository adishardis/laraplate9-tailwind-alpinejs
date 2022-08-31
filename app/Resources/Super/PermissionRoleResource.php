<?php

namespace App\Resources\Super;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionRoleResource extends JsonResource
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
            'permission_id' => $this->permission_id,
            'role_id' => $this->role_id,
        ];
    }
}
