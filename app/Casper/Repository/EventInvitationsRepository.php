<?php

namespace App\Casper\Repository;

use App\Casper\Model\Event;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;

class EventInvitationsRepository
{
    /**
     * Finds invitations by given token
     *
     * @param $token
     * @return EventInvitation|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function findByToken($token)
    {
        return EventInvitation::where('token', $token)->first();
    }

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

    /**
     * Fetch invitation instance for given user and event
     *
     * @param Event $event
     * @param User $user
     * @return mixed
     */
    public function fetchByEventAndUser(Event $event, User $user)
    {
        return $event->invitations()
            ->where('invited_id', $user->id)
            ->first();
    }
}
