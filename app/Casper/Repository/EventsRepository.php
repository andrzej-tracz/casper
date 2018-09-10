<?php

namespace App\Casper\Repository;

use App\Casper\Model\Event;
use Carbon\Carbon;

class EventsRepository
{
    /**
     * Select all public upcoming events, which starts within given time period
     *
     * @param int $days
     * @return mixed
     */
    public function fetchPublicUpcomingEvents($days = 30)
    {
        return Event::onlyPublic()
            ->whereBetween('date', [
                Carbon::now(), Carbon::now()->addDays($days)
            ])
            ->get();
    }

    /**
     * Fetch nearest events which will appear within given radius of provided coordinates
     *
     * @param $lat
     * @param $lng
     * @param int $radius
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchNearestEvents($lat, $lng, $radius = 5)
    {
        return Event::query()
            ->selectRaw(
                '(6371 * acos( cos( radians(?) ) * cos( radians( geo_lat ) ) 
                * cos( radians( geo_lng ) - radians(?) ) + sin( radians(?) ) 
                * sin(radians(geo_lat)) ) ) AS distance, *',
                [
                    $lat, $lng, $lat
                ]
            )
            ->where('distance', '<=', $radius)
            ->get();
    }
}
