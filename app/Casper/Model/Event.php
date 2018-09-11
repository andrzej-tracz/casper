<?php

namespace App\Casper\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const EVENT_TYPE_PUBLIC = 'public';
    const EVENT_TYPE_PRIVATE = 'public';

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

    protected $casts = [
        'geo_lat' => 'double',
        'geo_lng' => 'double',
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

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyPublic(Builder $query)
    {
        return $query->where('event_type', static::EVENT_TYPE_PUBLIC);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyPrivate(Builder $query)
    {
        return $query->where('event_type', static::EVENT_TYPE_PRIVATE);
    }

    /**
     * Determinate if current event is public
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->event_type === static::EVENT_TYPE_PUBLIC;
    }
}
