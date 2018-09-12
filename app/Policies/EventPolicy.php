<?php

namespace App\Policies;

use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Repository\GuestRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @var GuestRepository
     */
    protected $guests;

    public function __construct(GuestRepository $repository)
    {
        $this->guests = $repository;
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

        return $user->id === $event->user_id;
    }
}
