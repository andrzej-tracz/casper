<?php

namespace App\Http\Controllers\API;

use App\Casper\Repository\EventsRepository;
use App\Casper\Repository\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(UserRepository $repository, EventsRepository $events, Request $request, Guard $guard)
    {
        /** @var $user \App\Casper\Model\User */
        $user = $guard->user();
        $search = $request->input('search');
        $eventId = $request->input('event_id');
        $event = null;
        $excludeIds = [];

        if ($user) {
            $excludeIds[] = $user->id;
        }

        if ($eventId) {
            $event = $events->find($eventId);
        }

        $users = $repository->searchForInvitations($event, $search, $excludeIds);

        return User::collection($users);
    }
}
