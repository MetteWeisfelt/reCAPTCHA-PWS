<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ControlImage extends JsonResource
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
            'categoryName' => $this->category->name,
            'path' => $this->path,
            'name' => $this->name,
            'height' => $this->height,
            'width' => $this->width
        ];
    }
}
