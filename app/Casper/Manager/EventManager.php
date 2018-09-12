<?php

namespace App\Casper\Manager;

use App\Casper\Model\Event;
use App\Casper\Model\Guest;
use App\Casper\Model\User;
use App\Casper\Repository\GuestRepository;
use Illuminate\Validation\ValidationException;

class EventManager
{
    /**
     * @var GuestRepository
     */
    protected $guests;

    public function __construct(GuestRepository $guests)
    {
        $this->guests = $guests;
    }

    /**
     * Creates new event instance for given user
     *
     * @param User $user
     * @param array $attributes
     * @return Event
     */
    public function create(User $user, array $attributes)
    {
        $event = new Event();
        $event->fill($attributes);
        $event->user()->associate($user);
        $event->save();

        return $event;
    }

    /**
     * Updates given event with provided attributes
     *
     * @param Event $event
     * @param array $attributes
     * @return Event
     */
    public function update(Event $event, array $attributes)
    {
        $event->fill($attributes);
        $event->save();

        return $event;
    }

    /**
     * Creates new guest entry for a given event and user
     *
     * @param Event $event
     * @param User $user
     * @return Guest
     */
    public function joinToEvent(Event $event, User $user)
    {
        if ($this->guests->isGuestOfEvent($event, $user)) {
            throw ValidationException::withMessages([
                __('You are already joined to this event.')
            ]);
        }

        if ($event->guests()->count() >= $event->max_guests_number) {
            throw ValidationException::withMessages([
                __('There is no free places for this event.')
            ]);
        }

        $guest = new Guest();
        $guest->user()->associate($user);
        $guest->event()->associate($event);
        $guest->save();

        return $guest;
    }

    /**
     * Removes given event
     *
     * @param Event $event
     * @return bool|null
     * @throws \Exception
     */
    public function destroy(Event $event)
    {
        return $event->delete();
    }
}
