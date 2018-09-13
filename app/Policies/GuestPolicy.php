<?php

namespace App\Policies;

use App\Casper\Model\Guest;
use App\Casper\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given event can be deleted by the user.
     *
     * @param User $user
     * @param Guest $guest
     * @return bool
     */
    public function destroy(User $user, Guest $guest)
    {
        return $user->id === $guest->event->user_id;
    }
}
