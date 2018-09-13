<?php

namespace App\Http\Controllers\API;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Repository\EventsRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateEventRequest;
use App\Http\Requests\Events\SearchEventsRequest;
use App\Http\Requests\Events\UpdateEventRequest;
use Illuminate\Contracts\Auth\Guard;

class EventsController extends Controller
{
    /**
     * @var EventManager
     */
    protected $manger;

    /**
     * @var EventsRepository
     */
    protected $repository;

    /**
     * @var Guard
     */
    protected $guard;

    public function __construct(
        EventManager $manager,
        EventsRepository $repository,
        Guard $guard
    ) {
        $this->manger = $manager;
        $this->repository = $repository;
        $this->guard = $guard;
    }

    /**
     * Retrives list of all Events created by authenticated User
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        /** @var $user User*/
        $user = $this->guard->user();
        $events = $this->repository->fetchUserEvents($user);

        return $this->respondWithCollection($events);
    }

    /**
     * Shows details of of given Event
     *
     * @param Event $event
     * @return \App\Http\Resources\Event
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load('guests', 'guests.user', 'invitations', 'invitations.invited');

        return $this->respondWithItem($event);
    }

    /**
     * Creates new Event
     *
     * @param CreateEventRequest $request
     * @param Guard $guard
     * @return \App\Http\Resources\Event
     */
    public function store(CreateEventRequest $request, Guard $guard)
    {
        $attributes = $request->validated();
        $user = $guard->user();
        $event = $this->manger->create($user, $attributes);

        return $this->respondWithItem($event);
    }

    /**
     * Updates single event
     *
     * @param Event $event
     * @param UpdateEventRequest $request
     * @return \App\Http\Resources\Event
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Event $event, UpdateEventRequest $request)
    {
        $this->authorize('update', $event);
        $attributes = $request->validated();
        $event = $this->manger->update($event, $attributes);

        return $this->respondWithItem($event);
    }

    /**
     * Removes single event
     *
     * @param Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Event $event)
    {
        $this->authorize('destroy', $event);

        $this->manger->destroy($event);

        return $this->respondNoContent();
    }

    /**
     * Creates response with nearest events for given coordinates and radius
     *
     * @param SearchEventsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function searchNearest(SearchEventsRequest $request)
    {
        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');
        $radius = $request->query->get('radius');

        $events = $this->repository->fetchNearestEvents($lat, $lng, $radius);

        return $this->respondWithCollection($events);
    }

    /**
     * Create resource response with single event
     *
     * @param $event
     *
     * @return \App\Http\Resources\Event
     */
    protected function respondWithItem($event)
    {
        return new \App\Http\Resources\Event($event);
    }

    /**
     * Create response with collection of events
     *
     * @param $events
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    protected function respondWithCollection($events)
    {
        return \App\Http\Resources\Event::collection($events);
    }
}
