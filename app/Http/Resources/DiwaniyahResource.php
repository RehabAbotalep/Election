<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiwaniyahResource extends JsonResource
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
            'owner' => $this->owner,
            'occasion' => $this->occasion,
            'person' => $this->person,
            'address' => $this->address,
            'region' => $this->region,
            'date' => $this->date,
        ];
    }
}
