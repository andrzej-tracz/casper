<?php

namespace App\Policies;

use App\Casper\Model\Event;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;
use App\Casper\Repository\EventInvitationsRepository;
use App\Casper\Repository\GuestRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @var GuestRepository
     */
    protected $guests;

    /**
     * @var EventInvitationsRepository
     */
    protected $invitations;

    public function __construct(GuestRepository $repository, EventInvitationsRepository $invitations)
    {
        $this->guests = $repository;
        $this->invitations = $invitations;
    }

    /**
     * Determine if the given event can be updated by the user.
     *
     * @param User $user
     * @param Event $event
     *
     * @return bool
     */
    public function view(?User $user, Event $event)
    {
        if ($event->isPublic()) {
            return true;
        }

        if (null == $user) {
            return false;
        }

        if ($user && $this->guests->isGuestOfEvent($event, $user)) {
            return true;
        }

        if ($user && $invitation = $this->invitations->fetchByEventAndUser($event, $user)) {
            return $invitation instanceof EventInvitation;
        }

        return $user->id === $event->user_id;
    }

    /**
     * Determine if the given event can be updated by the user.
     *
     * @param User $user
     * @param Event $event
     *
     * @return bool
     */
    public function update(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine if the given event can be deleted by the user.
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function destroy(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }

    /**
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function join(User $user, Event $event)
    {
        if ($event->isPublic()) {
            return true;
        }

        if ($invitation = $this->invitations->fetchByEventAndUser($event, $user)) {
            return $invitation instanceof EventInvitation;
        }

        return $user->id === $event->user_id;
    }

    /**
     * Determine if the given user can create event invitations
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function invitations(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }
}
