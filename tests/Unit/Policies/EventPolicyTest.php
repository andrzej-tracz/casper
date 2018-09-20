<?php

namespace Tests\Unit\Policies;

use App\Casper\Model\Event;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\Guest;
use App\Casper\Model\User;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class EventPolicyTest
 * @package Tests\Unit\Policies
 *
 * @group policies
 */
class EventPolicyTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /**
     * @var EventPolicy
     */
    protected $policy;

    public function setUp()
    {
        parent::setUp();
        $this->policy = app(EventPolicy::class);
    }

    public function tearDown()
    {
        $this->policy = null;
    }

    /**
     * @test
     */
    public function it_allows_for_view_when_event_is_public()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'event_type' => Event::EVENT_TYPE_PUBLIC
        ]);

        $this->assertTrue($event->isPublic());
        $this->assertTrue($this->policy->view(null, $event));
    }

    /**
     * @test
     */
    public function it_dont_allows_for_guest_view_when_event_is_private()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'event_type' => Event::EVENT_TYPE_PRIVATE
        ]);

        $this->assertFalse($event->isPublic());
        $this->assertFalse($this->policy->view(null, $event));
    }

    /**
     * @test
     */
    public function it_allows_for_view_for_guests()
    {
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'event_type' => Event::EVENT_TYPE_PRIVATE
        ]);

        $guest = new Guest();
        $guest->event()->associate($event);
        $guest->user()->associate($other);
        $guest->save();

        $this->assertFalse($event->isPublic());
        $this->assertTrue($this->policy->view($other, $event));
    }

    /**
     * @test
     */
    public function it_allows_for_view_for_invited_users()
    {
        $invitation = factory(EventInvitation::class)->create();
        $event = $invitation->event;
        $event->event_type = Event::EVENT_TYPE_PRIVATE;
        $event->save();

        $this->assertFalse($event->isPublic());
        $this->assertTrue($this->policy->view($invitation->invited, $event));
    }

    /**
     * @test
     */
    public function it_allows_for_view_for_creators()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'event_type' => Event::EVENT_TYPE_PRIVATE
        ]);

        $this->assertFalse($event->isPublic());
        $this->assertTrue($this->policy->view($user, $event));
    }

    /**
     * @test
     */
    public function it_allows_for_update_for_creators()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->update($user, $event));
    }

    /**
     * @test
     */
    public function it_allows_for_destroy_for_creators()
    {
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->destroy($user, $event));
    }

    /**
     * @test
     */
    public function it_allows_join_to_private_events_only_when_invitation_exists()
    {
        $user = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();
        $event = $invitation->event;
        $event->event_type = Event::EVENT_TYPE_PRIVATE;
        $event->save();

        $this->assertFalse($event->isPublic());
        $this->assertTrue($this->policy->join($invitation->invited, $event));

        $this->assertFalse($this->policy->join($user, $event));
    }

}
