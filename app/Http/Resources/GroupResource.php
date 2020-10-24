<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        $voted_users = $this->electors->where('voted',1)->count();
        $users_count = $this->electors->count();
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'color'       => $this->color,
            'added_by'    => $this->addedBy->name,
            'voted_users' => $voted_users,
            'percentage'  => ($users_count != 0) ? round($voted_users/$users_count *100,1) : 0,
            'users_count' => $users_count,
            'created_at'  => $this->created_at,

        ];
    }
}
