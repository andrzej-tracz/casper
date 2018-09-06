<?php

namespace App\Casper\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'event_type',
        'place',
        'description',
        'date',
        'time',
        'duration_minutes',
        'max_guests_number',
        'geo_lat',
        'geo_lng',
        'applications_ends_at',
    ];

    protected $dates = [
        'applications_ends_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
