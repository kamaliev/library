<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'author' => $this->author->author,
            'title' => $this->title,
            'year' => $this->year,
            'created_at' => $this->created_at,
        ];
    }
}
