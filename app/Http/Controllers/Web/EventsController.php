<?php

namespace App\Http\Controllers\Web;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Repository\EventsRepository;
use App\Casper\Repository\GuestRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Validation\ValidationException;

class EventsController extends Controller
{
    /**
     * @var EventsRepository
     */
    protected $events;

    /**
     * @var GuestRepository
     */
    protected $guests;

    protected $guard;

    public function __construct(
        EventsRepository $repository,
        GuestRepository $guestRepository,
        Guard $guard
    ) {
        $this->events = $repository;
        $this->guests = $guestRepository;
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

    public function details(Event $event)
    {
        $user = $this->guard->user();
        $hasJoined = $this->guests->isGuestOfEvent($event, $user);
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

        if ($this->guests->isGuestOfEvent($event, $user)) {
            throw ValidationException::withMessages([
               __('You are already joined to this event.')
            ]);
        }

        if ($event->guests()->count() >= $event->max_guests_count) {
            throw ValidationException::withMessages([
                __('There is no free places for this event.')
            ]);
        }

        $manager->joinToEvent($event, $user);

        return back()->with('message', __('Successful join to event.'));
    }
}
