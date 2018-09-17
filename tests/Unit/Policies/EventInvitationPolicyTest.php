<?php

namespace Tests\Unit\Policies;

use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;
use App\Policies\EventInvitationPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventInvitationPolicyTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /**
     * @var EventInvitationPolicy
     */
    protected $policy;

    public function setUp()
    {
        parent::setUp();
        $this->policy = new EventInvitationPolicy();
    }

    public function tearDown()
    {
        $this->policy = null;
    }

    /**
     * @test
     */
    public function it_allows_for_view_for_creators()
    {
        $user = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();

        $invitation->creator()->associate($user);

        $this->assertNotNull($user->id);
        $this->assertNotNull($invitation->id);
        $this->assertTrue($this->policy->view($user, $invitation));
    }

    /**
     * @test
     */
    public function it_allows_for_view_for_invited()
    {
        $user = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();

        $invitation->invited()->associate($user);

        $this->assertNotNull($user->id);
        $this->assertNotNull($invitation->id);
        $this->assertTrue($this->policy->view($user, $invitation));
    }

    /**
     * @test
     */
    public function it_allows_for_visit_only_invited()
    {
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();

        $invitation->invited()->associate($user);

        $this->assertNotNull($user->id);
        $this->assertNotNull($invitation->id);
        $this->assertTrue($this->policy->visit($user, $invitation));
        $this->assertFalse($this->policy->visit($other, $invitation));
    }

    /**
     * @test
     */
    public function it_allows_for_destroy_only_creators()
    {
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();

        $invitation->creator()->associate($user);

        $this->assertNotNull($user->id);
        $this->assertNotNull($invitation->id);
        $this->assertTrue($this->policy->destroy($user, $invitation));
        $this->assertFalse($this->policy->destroy($other, $invitation));
    }
}
