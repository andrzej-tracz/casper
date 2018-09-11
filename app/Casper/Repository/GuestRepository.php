<?php

namespace App\Casper\Repository;

use App\Casper\Model\Event;
use App\Casper\Model\Guest;
use App\Casper\Model\User;

class GuestRepository
{
    /**
     * Determine if given user is already a guest of event
     *
     * @param Event $event
     * @param User $user
     * @return mixed
     */
    public function isGuestOfEvent(Event $event, User $user)
    {
        return Guest::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();
    }
}
