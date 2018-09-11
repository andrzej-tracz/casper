<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    public static $wrap = null;

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
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'event_type' => $this->resource->event_type,
            'place' => $this->resource->place,
            'description' => $this->resource->description,
            'date' => (string) $this->resource->date,
            'time' => $this->resource->time,
            'duration_minutes' => $this->resource->duration_minutes,
            'max_guests_number' => $this->resource->max_guests_number,
            'geo_lat' => $this->resource->geo_lat,
            'geo_lng' => $this->resource->geo_lng,
            'applications_ends_at' => (string) $this->resource->applications_ends_at,
            'created_at' => (string) $this->resource->created_at,
            'updated_at' => (string) $this->resource->updated_at,
        ];
    }
}
