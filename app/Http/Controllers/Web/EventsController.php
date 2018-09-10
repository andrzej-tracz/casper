<?php

namespace App\Http\Controllers\Web;

use App\Casper\Model\Event;
use App\Casper\Repository\EventsRepository;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * @var EventsRepository
     */
    protected $events;

    public function __construct(EventsRepository $repository)
    {
        $this->events = $repository;
    }

    public function upcomingEvents()
    {
        $upcomingEvents = $this->events->fetchPublicUpcomingEvents();

        return view('web.events.upcoming', [
            'events' => $upcomingEvents
        ]);
    }

    public function nearest()
    {
        return view('web.events.nearest');
    }

    public function details(Event $event)
    {
        return view('web.events.details', [
            'event' => $event
        ]);
    }
}
