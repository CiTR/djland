<?php

use Faker\Generator as Faker;

$factory->define(App\Song::class, function (Faker $faker) {
    return [
        "title"  => $faker->catchPhrase,
        "length" => $faker->numberBetween(2*60,6*60),
        "lyrics" => $faker->paragraphs($faker->numberBetween(1,5), true),
    ];
});
