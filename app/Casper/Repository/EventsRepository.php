<?php

namespace App\Casper\Repository;

use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Eloquent\Repository\AbstractEloquentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventsRepository extends AbstractEloquentRepository
{
    /**
     * Fetches all public upcoming events, which starts within given time period
     *
     * @param User|null $user
     * @param int $days
     * @return mixed
     */
    public function fetchPublicUpcomingEvents(User $user = null, $days = 30)
    {
        return $this->createUpcomingEventsQuery($user, $days)->get();
    }

    /**
     * Fetches nearest events which will appear within given radius of provided coordinates
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
     * Fetches events created by given user
     *
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchUserEvents(User $user)
    {
        return $user->events()
            ->selectRaw(
                'events.*, concat(events.date, " ", events.time) as event_date_time'
            )
            ->orderBy('event_date_time')
            ->get();
    }

    /**
     * Create initial query for upcoming events
     *
     * @param User|null $user
     * @param int $days
     * @return mixed
     */
    protected function createUpcomingEventsQuery(User $user = null, $days = 30)
    {
        return Event::where(function ($query) use ($user) {
            $query->onlyPublic();

            if ($user) {
                $this->includeUserScopedEvents($query, $user);
            }
        })
            ->where('date', '<=', Carbon::now()->addDays($days))
            ->selectRaw(
                'events.*, concat(events.date, " ", events.time) as event_date_time'
            )
            ->having('event_date_time', '>=', Carbon::now());
    }

    /**
     * Includes within given query events when user has been invited or joined as guest
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    protected function includeUserScopedEvents(Builder $query, User $user)
    {
        return $query->orWhere(function (Builder $q) use ($user) {
            $q->whereHas('guests', function (Builder $guests) use ($user) {
                return $guests->where('user_id', $user->id);
            })->orWhereHas('invitations', function (Builder $invitations) use ($user) {
                return $invitations->where('user_id', $user->id);
            })->orWhereHas('user', function (Builder $query) use ($user) {
                return $query->where('id', $user->id);
            });
        });
    }

    /**
     * Returns the Model class of repository
     *
     * @return string
     */
    protected function getModelClass()
    {
        return Event::class;
    }
}
