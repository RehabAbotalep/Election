<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PredefineDataResource;
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
            'id'           => $this->id,
            'name'         => $this->name,
            'user_name'    => $this->user_name,
            'phone'        => $this->phone,
            'percentage'   => $this->guarantee_percentage,
            //'type'         => $this->type->name,
            'type'         => new PredefineDataResource($this->type),
            'committee'    => new PredefineDataResource($this->committee),
            //'permissions'  => $this->getPermissionNames(),
            'permissions'  => PredefineDataResource::collection($this->permissions),
            'added_groups' => GroupResource::collection($this->addedGroups),
            'groups'       => GroupResource::collection($this->groups),


        ];
    }
}
