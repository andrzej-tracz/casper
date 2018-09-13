<?php

namespace App\Casper\Manager;

use App\Casper\Repository\EventInvitationsRepository;
use DB;
use App\Casper\Model\User;
use App\Casper\Model\EventInvitation;
use App\Casper\Notifications\EventInvitation as EventInvitationNotification;
use App\Casper\Model\Event;
use App\Casper\Exceptions\EventInvitation\UserAlreadyInvitedException;

class EventInvitationManager
{
    /**
     * @var EventManager
     */
    protected $eventManger;

    protected $repository;

    public function __construct(EventManager $manager, EventInvitationsRepository $repository)
    {
        $this->eventManger = $manager;
        $this->repository = $repository;
    }

    /**
     * Creates new event invitations
     *
     * @param User $creator
     * @param User $invited
     * @param Event $event
     *
     * @return EventInvitation
     * @throws UserAlreadyInvitedException
     */
    public function inviteUserToEvent(User $creator, User $invited, Event $event)
    {
        $oldInvitation = $this->repository->fetchByEventAndUser($event, $invited);

        if ($oldInvitation instanceof EventInvitation) {
            throw new UserAlreadyInvitedException();
        }

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
