<?php

namespace Tests\Unit\Casper\Manager;

use App\Casper\Manager\EventManager;
use App\Casper\Model\Event;
use App\Casper\Model\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class EventInvitationManagerTest
 * @package Tests\Unit\Casper\Manager
 *
 * @group events
 */
class EventManagerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var EventManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = app(EventManager::class);
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->manager = null;
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function it_thrown_an_exception_when_user_is_guest_of_event()
    {
        /** @var $user User */
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey()
        ]);

        $user->attendedEvents()->sync($event);

        $this->manager->joinToEvent($event, $user);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function it_thrown_an_exception_when_guest_count_is_full()
    {
        /** @var $user User */
        $user = factory(User::class)->create();
        $other = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'max_guests_number' => 1
        ]);

        $user->attendedEvents()->sync($event);
        $this->manager->joinToEvent($event, $other);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function it_thrown_an_exception_when_application_date_expires()
    {
        /** @var $user User */
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'max_guests_number' => 1,
            'applications_ends_at' => Carbon::now()->subSecond()
        ]);

        $this->manager->joinToEvent($event, $user);
    }

    /**
     * @test
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function it_thrown_an_exception_when_event_date_expires()
    {
        /** @var $user User */
        $user = factory(User::class)->create();
        $event = factory(Event::class)->create([
            'user_id' => $user->getKey(),
            'date' => Carbon::now()->format('Y-m-d'),
            'time' => Carbon::now()->subSecond()->format('H:i:s'),
        ]);

        $this->manager->joinToEvent($event, $user);
    }

}
