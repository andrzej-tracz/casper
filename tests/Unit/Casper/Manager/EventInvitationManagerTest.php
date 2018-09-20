<?php

namespace Tests\Unit\Casper\Manager;

use App\Casper\Manager\EventInvitationManager;
use App\Casper\Model\Event;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class EventInvitationManagerTest
 * @package Tests\Unit\Casper\Manager
 *
 * @group invitations
 */
class EventInvitationManagerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var EventInvitationManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = app(EventInvitationManager::class);
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->manager = null;
    }

    /**
     * @test
     * @expectedException \App\Casper\Exceptions\EventInvitation\UserAlreadyInvitedException
     */
    public function it_thrown_an_exception_when_user_already_invited()
    {
        $user = factory(User::class)->create();
        $invitation = factory(EventInvitation::class)->create();

        $this->manager->inviteUserToEvent($user, $invitation->invited, $invitation->event);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function it_thrown_an_exception_when_invitation_doesnt_have_new_status()
    {
        $invitation = factory(EventInvitation::class)->create();

        $invitation->status = EventInvitation::STATUS_ACCEPTED;
        $invitation->save();

        $this->manager->acceptInvitation($invitation);
    }

    /**
     * @test
     */
    public function it_invites_user_to_event()
    {
        $user = factory(User::class)->create();
        $invited = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey()
        ]);

        $invitation = $this->manager->inviteUserToEvent($user, $invited, $event);
        $this->assertTrue($invitation instanceof EventInvitation);
        $this->assertEquals(EventInvitation::STATUS_NEW, $invitation->status);
    }
}
