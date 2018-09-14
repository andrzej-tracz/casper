<?php

namespace App\Http\Controllers\Web;

use App\Casper\Manager\EventInvitationManager;
use App\Casper\Repository\EventInvitationsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Casper\Model\EventInvitation;

class EventInvitationsController extends Controller
{
    protected $repository;

    protected $manager;

    public function __construct(EventInvitationsRepository $repository, EventInvitationManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * Renders single invitation page
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($token)
    {
        $invitation = $this->repository->findByToken($token);

        if (!$invitation) {
            throw new ModelNotFoundException($invitation);
        }

        $this->authorize('visit', $invitation);

        if ($invitation->isAccepted()) {
            return redirect(route('web.event.details', $invitation->event));
        }

        return view('web.events.invitation', [
            'event' => $invitation->event,
            'invitation' => $invitation
        ]);
    }

    /**
     * Accepts invitation and join to event
     *
     * @param EventInvitation $invitation
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accept(EventInvitation $invitation)
    {
        $this->authorize('accept', $invitation);

        $this->manager->acceptInvitation($invitation);

        return back()->with('message', __('Invitation has been accepted.'));
    }
}
