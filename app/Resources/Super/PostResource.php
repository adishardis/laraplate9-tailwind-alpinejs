<?php

namespace App\Resources\Super;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => strlen($this->title) > 30 ? (substr($this->title, 0, 30).'...') : $this->title,
            'description' => strlen($this->description) > 50 ? (substr($this->description, 0, 50).'...') : $this->description,
            'status' => $this->status,
            'author_name' => $this->author ? $this->author->name : '',
            'summary_likes' => $this->summary ? $this->summary->likes : 0,
            'summary_dislikes' => $this->summary ? $this->summary->dislikes : 0,
            'summary_comments' => $this->summary ? $this->summary->comments : 0,
        ];
    }
}
