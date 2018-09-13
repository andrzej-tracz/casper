<?php

namespace App\Policies;

use App\Casper\Model\User;
use App\Casper\Model\EventInvitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given invitation can be viewed by the user.
     *
     * @param User $user
     * @param EventInvitation $invitation
     *
     * @return bool
     */
    public function view(User $user, EventInvitation $invitation)
    {
        return $this->isCreator($user, $invitation);
    }

    /**
     * Determine if the given invitation can be deleted by the user.
     *
     * @param User $user
     * @param EventInvitation $invitation
     *
     * @return bool
     */
    public function destroy(User $user, EventInvitation $invitation)
    {
        return $this->isCreator($user, $invitation);
    }

    /**
     * @param User $user
     * @param EventInvitation $invitation
     *
     * @return bool
     */
    public function accept(User $user, EventInvitation $invitation)
    {
        return $this->isInvited($user, $invitation);
    }

    /**
     * Check if given User creates Invitation
     *
     * @param User $user
     * @param EventInvitation $invitation
     *
     * @return bool
     */
    protected function isCreator(User $user, EventInvitation $invitation)
    {
        return $user->id === $invitation->creator_id;
    }

    /**
     * Checks if given User has been invited in given Invitation
     *
     * @param User $user
     * @param EventInvitation $invitation
     * @return bool
     */
    protected function isInvited(User $user, EventInvitation $invitation)
    {
        return $user->id === $invitation->invited_id;
    }
}
