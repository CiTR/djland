<?php

use Faker\Generator as Faker;

$factory->define(App\Episode::class, function (Faker $faker) {
    $start_time = $faker->dateTimeBetween('-1 year');

    $broadcast_types = [
        'Live',
        'Syndicated',
        'Rebroadcast',
        'Simulcast',
    ];

    return [
        'start_time'          => $start_time,
        'end_time'            => $start_time->modify(rand(1,2).' hour'),
        'title'               => implode(' ', $faker->words(rand(3,8))),
        'description'         => $faker->paragraph,
        'spokenword_duration' => $faker->numberBetween(0,60),
        'language'            => ($faker->boolean(95)) ? 'en' : $faker->languageCode,
        'broadcast_type'      => ($faker->boolean(75)) ? $broadcast_types[0] : $broadcast_types[array_rand($broadcast_types)],
        'is_published'        => $faker->boolean(90),
        'is_web_exclusive'    => $faker->boolean(5),
    ];
});
