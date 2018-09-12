<?php

use Faker\Generator as Faker;


$factory->define(\App\Casper\Model\Event::class, function (Faker $faker) {
    $date = \Carbon\Carbon::now()->addDays($faker->numberBetween(10, 30));

    return [
        'name' => $faker->sentence,
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