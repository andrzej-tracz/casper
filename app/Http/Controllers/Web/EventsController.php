<?php

namespace App\Http\Controllers\Web;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Repository\EventsRepository;
use App\Casper\Repository\GuestRepository;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Guard;

class EventsController extends Controller
{
    /**
     * @var EventsRepository
     */
    protected $events;

    /**
     * @var Guard
     */
    protected $guard;

    public function __construct(EventsRepository $repository, Guard $guard)
    {
        $this->events = $repository;
        $this->guard = $guard;
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

    public function details(Event $event, GuestRepository $guests)
    {
        /** @var $user User */
        $user = $this->guard->user();
        $hasJoined = $user && $guests->isGuestOfEvent($event, $user);
        $event->load('guests', 'guests.user');

        return view('web.events.details', [
            'event' => $event,
            'hasJoined' => $hasJoined,
            'canJoin' => $user && !$hasJoined
        ]);
    }

    public function join(Event $event, EventManager $manager)
    {
        /** @var $user User */
        $user = $this->guard->user();
        $manager->joinToEvent($event, $user);

        return back()->with('message', __('Successful join to event.'));
    }
}
