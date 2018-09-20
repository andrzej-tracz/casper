<?php

namespace Tests\Feature\API;

use App\Casper\Model\Event;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Class EventsManagementTest
 * @package Tests\Feature\API
 *
 * @group users
 */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_aborts_if_not_authenticated()
    {
        $response = $this->json('GET', 'panel/ajax/users-search');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function it_searches_users()
    {
        $user = factory(User::class)->create();
        $searched = User::where('id', '<>', $user->id)->orderByRaw('rand()')->first();
        $this->actingAs($user);

        $response = $this->json('GET', 'panel/ajax/users-search', [
            'search' => substr($searched->nickname, 0, strlen($searched->nickname) - 2),
        ]);

        $response->assertOk();
        $response->assertSee(json_encode([
            'id' => $searched->id,
            'nickname' => $searched->nickname,
        ]));
    }

    /**
     * @test
     */
    public function it_excludes_authenticated_user()
    {
        $user = factory(User::class)->create();
        $event = $user->events()->save(
            factory(Event::class)->make()
        );
        $this->actingAs($user);

        $response = $this->json('GET', 'panel/ajax/users-search', [
            'search' => 'a',
            'event_id' => $event->getKey(),
        ]);

        $response->assertOk();
        $users = $response->json('data');
        array_map(function ($result) use ($user) {
            $this->assertFalse($user->getKey() == $result['id']);
        }, $users);
    }
}
