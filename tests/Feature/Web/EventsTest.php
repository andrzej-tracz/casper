<?php

namespace Tests\Feature\Web;

use App\Casper\Model\Event;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class EventInvitationsTest
 * @package Tests\Feature\Web
 *
 * @group events
 */
class EventsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_renders_nearest_events_page()
    {
        $response = $this->get('nearest-events');
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_renders_details_page()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'event_type' => Event::EVENT_TYPE_PUBLIC
        ]);

        $response = $this->get("event/{$event->getKey()}");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_renders_details_page_for_private_events()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'event_type' => Event::EVENT_TYPE_PRIVATE
        ]);

        $response = $this->get("event/{$event->getKey()}");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_aborts_if_other_users_for_private_events()
    {
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $this->actingAs($other);

        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'event_type' => Event::EVENT_TYPE_PRIVATE
        ]);

        $response = $this->get("event/{$event->getKey()}");
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function it_handles_joining_to_public_events()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'event_type' => Event::EVENT_TYPE_PUBLIC
        ]);

        $response = $this->post("event/join/{$event->getKey()}");
        $response->assertForbidden();

        $this->actingAs($user);
        $response = $this->post("event/join/{$event->getKey()}");
        $response->assertRedirect();
    }
}
