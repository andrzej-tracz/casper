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
        return $this->createUpcomingEventsQuery($days)->get();
    }

    /**
     * Fetch nearest events which will appear within given radius of provided coordinates
     *
     * @param $lat
     * @param $lng
     * @param int $radius
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchNearestEvents($lat, $lng, $radius = null)
    {
        if (is_null($radius)) {
            $radius = 5;
        }

        // TODO:: Attach private events here when invitations will be done
        return $this
            ->createUpcomingEventsQuery()
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(geo_lat)) 
                * cos(radians(geo_lng) - radians(?)) + sin(radians(?)) 
                * sin(radians(geo_lat)))) AS distance',
                [
                    $lat, $lng, $lat
                ]
            )
            ->having('distance', '<=', $radius)
            ->get();
    }

    /**
     * Create initial query for upcoming events
     *
     * @param int $days
     * @return mixed
     */
    protected function createUpcomingEventsQuery($days = 30)
    {
        return Event::onlyPublic()
            ->where('date', '<=', Carbon::now()->addDays($days))
            ->selectRaw(
                'events.*, concat(events.date, " ", events.time) as event_date_time'
            )
            ->having('event_date_time', '>=', Carbon::now());
    }
}
