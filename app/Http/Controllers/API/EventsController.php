<?php

namespace App\Http\Controllers\API;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Repository\EventsRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateEventRequest;
use App\Http\Requests\Events\SearchEventsRequest;
use Illuminate\Contracts\Auth\Guard;

class EventsController extends Controller
{
    /**
     * @var EventManager
     */
    protected $manger;

    public function __construct(EventManager $manager)
    {
        $this->manger = $manager;
    }

    public function store(CreateEventRequest $request, Guard $guard)
    {
        $attributes = $request->validated();
        $user = $guard->user();
        $event = $this->manger->create($user, $attributes);

        return [
            'event' => new \App\Http\Resources\Event($event)
        ];
    }

    public function update(Event $event, CreateEventRequest $request)
    {
        //
    }

    public function searchNearest(SearchEventsRequest $request, EventsRepository $repository)
    {
        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');
        $radius = $request->query->get('radius');

        $events = $repository->fetchNearestEvents($lat, $lng, $radius);

        return \App\Http\Resources\Event::collection($events);
    }
}
