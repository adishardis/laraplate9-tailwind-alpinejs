<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostSummaryResource extends JsonResource
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
            'post_id' => $this->post_id,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'comments' => $this->comments
        ];
    }
}
