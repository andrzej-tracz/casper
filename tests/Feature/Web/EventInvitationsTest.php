<?php

namespace Tests\Feature\Web;

use App\Casper\Model\EventInvitation;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Class EventInvitationsTest
 * @package Tests\Feature\Web
 *
 * @group invitations
 */
class EventInvitationsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_redirects_if_not_authenticated()
    {
        $invitation = factory(EventInvitation::class)->create();

        $response = $this->get("invitation/{$invitation->token}");

        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function it_aborts_if_not_authenticated_as_invited_user()
    {
        $user = factory(User::class)->make();
        $invitation = factory(EventInvitation::class)->create();
        $this->actingAs($user);

        $response = $this->get("invitation/{$invitation->token}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_shows_invitation_for_invited_user()
    {
        $invitation = factory(EventInvitation::class)->create();
        $this->actingAs($invitation->invited);

        $response = $this->get("invitation/{$invitation->token}");

        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_show_404_when_token_invalid()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user);

        $response = $this->get("invitation/invalid-invitaion-token");

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function it_redirects_if_already_accepted()
    {
        $invitation = factory(EventInvitation::class)->create();
        $this->actingAs($invitation->invited);

        $invitation->status = EventInvitation::STATUS_ACCEPTED;
        $invitation->save();

        $response = $this->get("invitation/{$invitation->token}");

        $response->assertRedirect("event/$invitation->event_id");
    }

    /**
     * @test
     */
    public function it_allows_accept_invitation_by_invited()
    {
        $invitation = factory(EventInvitation::class)->create();
        $this->actingAs($invitation->invited);

        $response = $this->put("invitation/{$invitation->id}/accept");

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function it_do_not_allows_accept_invitation_by_others()
    {
        $invitation = factory(EventInvitation::class)->create();
        $user = factory(User::class)->make();
        $this->actingAs($user);

        $response = $this->put("invitation/{$invitation->id}/accept");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
