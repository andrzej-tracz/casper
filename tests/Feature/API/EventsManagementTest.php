<?php

namespace Tests\Feature\API;

use App\Casper\Model\User;
use Carbon\Carbon;
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

        $response = $this->post('panel/ajax/events', [],  [
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
        ],  [
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
        ],  [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'event_type'
        ]);
    }
}
