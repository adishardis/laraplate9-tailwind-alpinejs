<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'path' => $this->path,
            'type' => $this->type,
            'url' => $this->url,
            'size' => $this->size,
        ];
    }
}
