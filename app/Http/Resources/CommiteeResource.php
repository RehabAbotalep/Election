<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommiteeResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'total_count' => $users_count,
            'voted_count' => $voted_users,
            'screening_ratio' => $this->screening_ratio,
        ];
    }
}
