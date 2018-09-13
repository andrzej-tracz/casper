<?php

namespace App\Http\Controllers\API;

use App\Casper\Exceptions\EventInvitation\UserAlreadyInvitedException;
use App\Casper\Manager\EventInvitationManager;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;
use App\Casper\Repository\EventInvitationsRepository;
use App\Casper\Repository\UserRepository;
use App\Http\Controllers\Controller;
use App\Casper\Model\Event;
use App\Http\Requests\EventInvitations\CreateEventInvitationRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EventInvitationsController extends Controller
{
    /**
     * @var EventInvitationsRepository
     */
    protected $repository;

    /**
     * @var EventInvitationManager
     */
    protected $manager;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var Guard
     */
    protected $guard;

    public function __construct(
        EventInvitationsRepository $repository,
        EventInvitationManager $manager,
        UserRepository $users,
        Guard $guard
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->users = $users;
        $this->guard = $guard;
    }

    /**
     * Shows invitations for given event
     *
     * @param Event $event
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Event $event)
    {
        $this->authorize('invitations', $event);

        $invitations = $this->repository->fetchByEvent($event);

        return $this->respondWithCollection($invitations);
    }

    /**
     * Creates new invitation for event
     *
     * @param CreateEventInvitationRequest $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateEventInvitationRequest $request, Event $event)
    {
        /** @var $user User */
        $user = $this->guard->user();
        $this->authorize('invitations', $event);

        $guest = $this->users->find($request->input('user_id'));

        try {
            $invitation = $this->manager->inviteUserToEvent($user, $guest, $event);

            return $this->respondWithItem($invitation, \Illuminate\Http\Response::HTTP_CREATED);
        } catch (UserAlreadyInvitedException $e) {
            throw ValidationException::withMessages([
                __('Selected user has been already invited to this event.')
            ]);
        }
    }

    /**
     * Removes invitation for event
     *
     * @param Event $event
     * @param EventInvitation $invitation
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Event $event, EventInvitation $invitation)
    {
        $this->authorize('destroy', $invitation);

        $this->manager->remove($invitation);

        return $this->respondNoContent();
    }

    /**
     * Accepts invitation and join to event
     *
     * @param EventInvitation $invitation
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accept(EventInvitation $invitation)
    {
        $this->authorize('accept', $invitation);

        $this->manager->acceptInvitation($invitation);

        return $this->respondWithItem($invitation);
    }

    /**
     * Create resource response with single event
     *
     * @param \App\Casper\Model\EventInvitation $invitation
     *
     * @param int $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem(EventInvitation $invitation, $status = Response::HTTP_OK)
    {
        return (new \App\Http\Resources\EventInvitation($invitation))
            ->response()
            ->setStatusCode($status);
    }

    /**
     * Create response with collection of events
     *
     * @param \Illuminate\Database\Eloquent\Collection $invitations
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    protected function respondWithCollection($invitations)
    {
        return \App\Http\Resources\EventInvitation::collection($invitations);
    }
}
