<?php

namespace Tests\Feature\API;

use App\Casper\Manager\EventInvitationManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use App\Casper\Notifications\EventInvitation as EventInvitationNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Class EventsManagementTest
 * @package Tests\Feature\API
 *
 * @group invitations
 */
class EventInvitationsManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_aborts_if_not_authenticated()
    {
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());

        $response = $this->json("POST", "panel/ajax/events/{$event->id}/invitations", []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ], true);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_view_invitation_of_others_events()
    {
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());

        $other = factory(User::class)->create();
        $this->actingAs($other);

        $response = $this->json("GET", "panel/ajax/events/{$event->id}/invitations");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_create_invitation_of_others_events()
    {
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());

        $other = factory(User::class)->create();
        $this->actingAs($other);

        $response = $this->json("POST", "panel/ajax/events/{$event->id}/invitations", [
            'user_id' => $user->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_shows_invitations_for_given_event()
    {
        /** @var $invitation \App\Casper\Model\EventInvitation */
        $invitation = factory(\App\Casper\Model\EventInvitation::class)->create();
        $this->actingAs($invitation->creator);

        $response = $this->json("GET", "panel/ajax/events/{$invitation->event->getKey()}/invitations");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_creates_invitation_when_user_is_creator_of_event()
    {
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());
        $this->actingAs($user);
        $invited = factory(User::class)->create();

        $response = $this->json("POST", "panel/ajax/events/{$event->id}/invitations", [
            'user_id' => $invited->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_validates_already_invited_users()
    {
        $user = factory(User::class)->create();
        $event = $user->events()->save(factory(Event::class)->make());
        $this->actingAs($user);

        $invited = factory(User::class)->create();
        factory(\App\Casper\Model\EventInvitation::class)->create([
            'creator_id' => $user->getKey(),
            'event_id' => $event->getKey(),
            'invited_id' => $invited->getKey(),
        ]);

        $response = $this->json("POST", "panel/ajax/events/{$event->id}/invitations", [
            'user_id' => $invited->id
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'errors' => [
                [
                    "Selected user has been already invited to this event."
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_can_accept_invitation_by_invited_user()
    {
        /** @var $manager EventInvitationManager */
        $manager = app(EventInvitationManager::class);
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());
        $invited = factory(User::class)->create();

        $this->actingAs($invited);

        \Notification::fake();
        $invitation = $manager->inviteUserToEvent($user, $invited, $event);
        \Notification::assertSentTo([$invited], EventInvitationNotification::class);

        $response = $this->put("panel/ajax/events/invitations/{$invitation->id}/accept", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_handles_invitation_removing()
    {
        /** @var $manager EventInvitationManager */
        $manager = app(EventInvitationManager::class);
        $user = User::first();
        $event = $user->events()->save(factory(Event::class)->make());
        $invited = factory(User::class)->create();

        $this->actingAs($user);

        $invitation = $manager->inviteUserToEvent($user, $invited, $event);

        $response = $this->delete("panel/ajax/events/{$event->id}/invitations/{$invitation->id}", [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
