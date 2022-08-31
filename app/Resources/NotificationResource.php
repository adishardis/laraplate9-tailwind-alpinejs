<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'type' => $this->type,
            'subject' => $this->subject,
            'message' => $this->message,
            'read_at' => $this->read_at ? $this->read_at->format('Y-m-d H:i') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
