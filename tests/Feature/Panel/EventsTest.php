<?php

namespace Tests\Feature\Panel;

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
    public function it_renders_event_page_in_user_panel()
    {
        $response = $this->get('panel/events');
        $response->assertRedirect('login');

        $user = factory(User::class)->create();

        $this->actingAs($user);
        $response = $this->get('panel/events');
        $response->assertOk();
    }
}
