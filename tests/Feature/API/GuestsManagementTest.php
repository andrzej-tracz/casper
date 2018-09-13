<?php

namespace Tests\Feature\API;

use App\Casper\Manager\EventManager;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Casper\Model\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventsManagementTest
 * @package Tests\Feature\API
 *
 * @group guests
 */
class GuestsManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_aborts_if_not_authenticated()
    {
        $user = User::first();
        $invited = factory(User::class)->create();
        $event = $user->events()->save(factory(Event::class)->make());

        /** @var $manager EventManager */
        $manager = app(EventManager::class);
        $guest = $manager->joinToEvent($event, $invited);

        $response = $this->delete("panel/ajax/guests/{$guest->id}", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ], true);
    }

    /**
     * @test
     */
    public function it_aborts_when_removing_guest_from_other_user_event()
    {
        $user = User::first();
        $invited = factory(User::class)->create();
        $event = $user->events()->save(factory(Event::class)->make());

        /** @var $manager EventManager */
        $manager = app(EventManager::class);
        $guest = $manager->joinToEvent($event, $invited);

        $other = factory(User::class)->create();
        $this->actingAs($other);

        $response = $this->delete("panel/ajax/guests/{$guest->id}", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_removes_guest_when_user_is_creator_of_event()
    {
        $user = User::first();
        $invited = factory(User::class)->create();
        $event = $user->events()->save(factory(Event::class)->make());

        /** @var $manager EventManager */
        $manager = app(EventManager::class);
        $guest = $manager->joinToEvent($event, $invited);

        $this->actingAs($user);

        $response = $this->delete("panel/ajax/guests/{$guest->id}", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('guests', [
           'id' => $guest->id
        ]);
    }
}
