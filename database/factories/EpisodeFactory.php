<?php

use Faker\Generator as Faker;

$factory->define(App\Episode::class, function (Faker $faker) {
    $start_datetime = $faker->dateTimeBetween('-1 year');

    $broadcast_types = config('djland.broadcast_types');

    return [
        'start_datetime'      => $start_datetime,
        'end_datetime'        => $start_datetime->modify(rand(1,2).' hour'),
        'title'               => implode(' ', $faker->words(rand(3,8))),
        'description'         => $faker->paragraph,
        'spokenword_duration' => $faker->numberBetween(0,60),
        'language'            => ($faker->boolean(95)) ? 'en' : $faker->languageCode,
        'broadcast_type'      => ($faker->boolean(75)) ? array_keys($broadcast_types)[0] : array_rand($broadcast_types),
        'is_published'        => $faker->boolean(90),
        'is_web_exclusive'    => $faker->boolean(5),
    ];
});
