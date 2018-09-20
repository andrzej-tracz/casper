<?php

namespace Tests\Feature\API;

use App\Casper\Model\Event;
use App\Casper\Model\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Class EventsManagementTest
 * @package Tests\Feature\API
 *
 * @group events
 */
class EventsManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_aborts_if_not_authenticated()
    {
        $response = $this->post('panel/ajax/events', [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ], true);
    }

    /**
     * @test
     */
    public function it_returns_user_events()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get('panel/ajax/events', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $content = $response->content();

        array_map(function ($event) use ($user) {
            $this->assertEquals($event['user_id'], $user->id);
        }, array_get(json_decode($content, true), 'data'));
    }

    /**
     * @test
     */
    public function it_respond_with_event_details()
    {
        $user = User::first();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user);
        $response = $this->get('panel/ajax/events/' . $event->id, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
           'data' => [
               'id' => $event->id,
               'name' => $event->name,
           ]
        ]);
    }

    /**
     * @test
     */
    public function it_updates_an_event()
    {
        $user = User::first();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user);
        $response = $this->put('panel/ajax/events/' . $event->id, [
            'name' => 'Lorem ipsum dolore sit'
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $event->id,
                'name' => 'Lorem ipsum dolore sit',
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_removes_an_event()
    {
        $user = User::first();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user);
        $response = $this->delete('panel/ajax/events/' . $event->id, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_searches_a_nearest_events()
    {
        $user = User::first();
        $faker = Factory::create();
        $lat = $faker->latitude;
        $lng = $faker->longitude;

        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->addWeek()->format('Y-m-d'),
            'event_type' => Event::EVENT_TYPE_PUBLIC,
            'geo_lat' => $lat,
            'geo_lng' => $lng,
        ]);

        $response = $this->json('GET', 'event/ajax/nearest-events-search', [
            'lat' => $lat + 0.04, // about ~4km distance
            'lng' => $lng
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                [
                    'id' => $event->id,
                    'name' => $event->name,
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_creates_an_event_when_data_is_valid()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post('panel/ajax/events', [
            'name' => 'Test Event',
            'event_type' => 'public',
            'place' => 'Warsaw',
            'description' => 'Some test event',
            'date' => Carbon::now()->addMonth()->format('Y-m-d'),
            'time' => Carbon::now()->addMonth()->format('H:i'),
            'duration_minutes' => 60,
            'max_guests_number' => 50,
            'geo_lat' => 52.2297,
            'geo_lng' => 21.0122,
            'applications_ends_at' => Carbon::now()->addWeeks(2)->format('Y-m-d'),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'data' => [
                'name' => 'Test Event',
                'event_type' => 'public',
                'place' => 'Warsaw',
                'description' => 'Some test event',
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_returns_a_list_of_errors_for_bad_request()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post('panel/ajax/events', [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
           'name',
           'event_type',
           'place',
           'description',
           'date',
           'time',
           'duration_minutes',
           'max_guests_number',
           'applications_ends_at',
        ]);
    }

    /**
     * @test
     */
    public function it_validates_geo_coordinates_when_provided()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post('panel/ajax/events', [
            'geo_lat' => 'bad_lat',
            'geo_lng' => 'bad_lng',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'geo_lat',
            'geo_lng',
        ]);
    }

    /**
     * @test
     */
    public function it_validates_event_type()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post('panel/ajax/events', [
            'event_type' => 'invalid_event_type',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'event_type'
        ]);
    }
}
