<?php

namespace App\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'comment' => $this->comment,
            'is_edited' => $this->is_edited,
            'user_name' => $this->user ? $this->user->name : '',
            'childrens' => CommentResource::collection($this->childrens),
        ];
    }
}
