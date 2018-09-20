<?php

use Faker\Generator as Faker;

$factory->define(\App\Casper\Model\Event::class, function (Faker $faker) {
    $date = \Carbon\Carbon::now()->addDays($faker->numberBetween(10, 30));
    $creator = factory(\App\Casper\Model\User::class)->create();

    return [
        'name' => $faker->sentence,
        'user_id' => $creator->id,
        'event_type' => array_random(['private', 'public']),
        'place' => $faker->city,
        'description' => $faker->sentence(30),
        'date' => $date,
        'time' => $faker->time('H:i'),
        'duration_minutes' => $faker->numberBetween(1, 360),
        'max_guests_number' => $faker->numberBetween(1,300),
        'applications_ends_at' => $date->copy()->subDays(2),
        'geo_lat' => $faker->latitude,
        'geo_lng' => $faker->longitude,
    ];
});

$factory->define(\App\Casper\Model\EventInvitation::class, function (Faker $faker) {
    $creator = factory(\App\Casper\Model\User::class)->create();
    $event = factory(\App\Casper\Model\Event::class)->create([
        'user_id' => $creator->id
    ]);
    $invited = factory(\App\Casper\Model\User::class)->create();

    return [
        'event_id' => $event->id,
        'creator_id' => $creator->id,
        'invited_id' => $invited->id,
    ];
});

$factory->define(\App\Casper\Model\Guest::class, function (Faker $faker) {
    $creator = factory(\App\Casper\Model\User::class)->create();
    $event = factory(\App\Casper\Model\Event::class)->create([
        'user_id' => $creator->id
    ]);
    $guest = factory(\App\Casper\Model\User::class)->create();

    return [
        'event_id' => $event->id,
        'user_id' => $guest->id,
    ];
});
