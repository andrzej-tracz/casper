<?php

namespace App\Http\Controllers\API;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateEventRequest;
use Illuminate\Contracts\Auth\Guard;

class EventsController extends Controller
{
    /**
     * @var EventManager
     */
    protected $manger;

    public function __construct(EventManager $manager)
    {
        $this->manger = $manager;
    }

    public function store(CreateEventRequest $request, Guard $guard)
    {
        $attributes = $request->validated();
        $user = $guard->user();
        $event = $this->manger->create($user, $attributes);

        return [
            'event' => $event
        ];
    }

    public function update(Event $event, CreateEventRequest $request)
    {
        //
    }
}
