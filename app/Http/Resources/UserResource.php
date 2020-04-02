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
        $response = [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'name' => $this->firstname . ' ' . $this->lastname,
            'description' => $this->description,
            'age' => $this->age,
            'sex' => $this->sex,
            'photo' => $this->photo
        ];

        $tag = $this->tag;
        if ($tag)
            $response['tag'] = [
                'name' => $tag->name,
                'description' => $tag->description
            ];
        else
            $response['tag'] = null;


        if ($this->getAttribute('calculatedDistance'))
            $response['distance'] = $this->getAttribute('calculatedDistance'); // If has calculated distance -> adding to JSON

        return $response;
    }
}
