<?php

use Faker\Generator as Faker;

$factory->define(App\EpisodeItem::class, function (Faker $faker) {
    return [];
});

$factory->state(App\EpisodeItem::class, 'without_song', function (Faker $faker) {
    return [
        'artist'   => $faker->company,
        'title'    => $faker->catchPhrase,
        'language' => ($faker->boolean(95)) ? 'en' : $faker->languageCode,
    ];
});


$factory->state(App\EpisodeItem::class, 'random_song', function (Faker $faker) {
    return [
        'song_id' => App\Song::inRandomOrder()->first()->id,
    ];
});
