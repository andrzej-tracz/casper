<?php

namespace Tests\Unit\Policies;

use App\Casper\Model\Event;
use App\Casper\Model\Guest;
use App\Casper\Model\User;
use App\Policies\GuestPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GuestPolicyTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /**
     * @var GuestPolicy
     */
    protected $policy;

    public function setUp()
    {
        parent::setUp();
        $this->policy = app(GuestPolicy::class);
    }

    public function tearDown()
    {
        $this->policy = null;
    }

    /**
     * @test
     */
    public function it_allows_for_destroy_for_creators()
    {
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
        ]);

        $guest = factory(Guest::class)->create([
            'event_id' => $event->id,
            'user_id' => $other->id,
        ]);

        $this->assertTrue($this->policy->destroy($user, $guest));
        $this->assertFalse($this->policy->destroy($other, $guest));
    }
}
