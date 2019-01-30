<?php

use Faker\Generator as Faker;

$factory->define(App\AdSchedule::class, function (Faker $faker) {
    $ad_types = config('djland.ad_types');
    $time = $faker->time();

    $return = [
        'name'                  => $faker->word,
        'type'                  => array_rand($ad_types),
        'description'           => $faker->sentence,
        'minutes_into_show'     => $faker->numberBetween(-59,59),
        'minutes_past_hour'     => $faker->numberBetween(0,60),
        'time_start'            => $faker->time('H:i:s', $time),
        'time_end'              => $time,
        'active_datetime_start' => $faker->dateTimeBetween('-1 year', '+3 months'),
        'active_datetime_end'   => $faker->dateTimeBetween('-2 months', '+3 months'),
    ];

    if ($faker->boolean) {
        unset($return['minutes_into_show']);
    } else {
        unset($return['minutes_past_hour']);
    }

    if ($faker->boolean) {
        unset($return['time_end']);
    }

    if ($faker->boolean) {
        unset($return['time_start']);
    }

    if ($faker->boolean) {
        unset($return['active_datetime_start']);
    }

    if ($faker->boolean) {
        unset($return['active_datetime_end']);
    }
});
