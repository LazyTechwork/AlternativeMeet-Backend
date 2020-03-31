<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'description' => $this->description,
            'geo' => $this->geo,
            'birthday' => $this->birthday,
            'sex' => $this->sex,
            'avatar' => $this->avatar
        ];
    }
}
