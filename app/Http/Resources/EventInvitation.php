<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventInvitation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'invited_id' => $this->resource->invited_id,
            'event_id' => $this->resource->event_id,
            'event' => $this->whenLoaded('event', new Event($this->resource->event)),
            'creator_id' => $this->resource->creator_id,
            'status' => $this->resource->status,
            'created_at' => (string) $this->resource->created_at,
            'updated_at' => (string) $this->resource->updated_at,
        ];
    }
}
