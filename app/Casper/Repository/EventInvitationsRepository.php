<?php

namespace App\Casper\Repository;

use App\Casper\Model\Event;

class EventInvitationsRepository
{
    /**
     * @param Event $event
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchByEvent(Event $event)
    {
        return $event->invitations()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
