<?php

namespace App\Http\Controllers\Web;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Repository\EventsRepository;
use App\Casper\Repository\GuestRepository;
use App\Http\Controllers\Controller;
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

    /**
     * Renders Upcoming Events page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upcomingEvents()
    {
        $upcomingEvents = $this->events->fetchPublicUpcomingEvents();

        return view('web.events.upcoming', [
            'events' => $upcomingEvents
        ]);
    }

    /**
     * Renders Nearest Events page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nearest()
    {
        return view('web.events.nearest');
    }

    /**
     * Renders Event details page
     *
     * @param Event $event
     * @param GuestRepository $guests
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Handles joining to public events
     *
     * @param Event $event
     * @param EventManager $manager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Event $event, EventManager $manager)
    {
        /** @var $user User */
        $user = $this->guard->user();
        $manager->joinToEvent($event, $user);

        return back()->with('message', __('Successful join to event.'));
    }
}
