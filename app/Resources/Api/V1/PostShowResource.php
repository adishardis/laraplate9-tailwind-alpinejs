<?php

namespace App\Resources\Api\V1;

use App\Resources\PostSummaryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostShowResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'is_edited' => $this->is_edited,
            'author_name' => $this->author ? $this->author->name : '',
            'summary' => new PostSummaryResource($this->summary),
        ];
    }
}
