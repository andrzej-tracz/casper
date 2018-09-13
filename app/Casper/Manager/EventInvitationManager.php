<?php

namespace App\Casper\Manager;

use DB;
use App\Casper\Model\User;
use App\Casper\Model\EventInvitation;
use App\Casper\Notifications\EventInvitation as EventInvitationNotification;
use App\Casper\Model\Event;

class EventInvitationManager
{
    /**
     * @var EventManager
     */
    protected $eventManger;

    public function __construct(EventManager $manager)
    {
        $this->eventManger = $manager;
    }

    /**
     * Creates new event invitations
     *
     * @param User $creator
     * @param User $invited
     * @param Event $event
     *
     * @return EventInvitation
     */
    public function inviteUserToEvent(User $creator, User $invited, Event $event)
    {
        try {
            DB::beginTransaction();

            $invitation = new EventInvitation();
            $invitation->invited()->associate($invited);
            $invitation->creator()->associate($creator);
            $invitation->event()->associate($event);
            $invitation->save();

            $invited->notify(
                new EventInvitationNotification($invitation)
            );

            DB::commit();

            return $invitation;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Removes invitation
     *
     * @param EventInvitation $invitation
     * @return bool|null
     */
    public function remove(EventInvitation $invitation)
    {
        return $invitation->delete();
    }

    /**
     * Accepts event invitation and creating an event's guest instance
     *
     * @param EventInvitation $invitation
     * @return \App\Casper\Model\Guest
     */
    public function acceptInvitation(EventInvitation $invitation)
    {
        $event = $invitation->event;
        $invited = $invitation->invited;

        $guest = $this->eventManger->joinToEvent($event, $invited);

        $invitation->status = EventInvitation::STATUS_ACCEPTED;
        $invitation->save();

        return $guest;
    }
}
