<?php

namespace App\Http\Controllers\API;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Guest;
use App\Http\Controllers\Controller;

class GuestController extends Controller
{
    /**
     * Removes single guest
     *
     * @param Guest $guest
     *
     * @param EventManager $manager
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Guest $guest, EventManager $manager)
    {
        $this->authorize('destroy', $guest);

        $manager->removeGuest($guest);

        return $this->respondNoContent();
    }
}
