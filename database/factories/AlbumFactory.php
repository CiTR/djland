<?php

use Faker\Generator as Faker;

$factory->define(App\Album::class, function (Faker $faker) {
    return [
        "artist"      => $faker->company,
        "title"       => $faker->catchPhrase,
        "label"       => $faker->company,
        "catalog"     => $faker->randomNumber(5),
        "description" => $faker->sentence(),
    ];
});
