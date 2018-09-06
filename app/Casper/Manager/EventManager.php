<?php

namespace App\Casper\Manager;

use App\Casper\Model\Event;
use App\Casper\Model\User;

class EventManager
{
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
}
