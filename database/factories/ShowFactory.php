<?php

use Faker\Generator as Faker;

$factory->define(App\Show::class, function (Faker $faker) {
    $start = $faker->time;
    return [
        'title'       => "The {$faker->unique()->company} Show",
        'weekday'     => $faker->numberBetween(0,6),
        'start_time'  => $start,
        'end_time'    => date('H:i:s',(strtotime($start)+(60*60*$faker->numberBetween(1,3)))),
        'last_show'   => null,
        'is_active'   => $faker->boolean,
        'is_explicit' => $faker->boolean,
        'website'     => $faker->domainName,
        'rss'         => $faker->url,
        'podcast_xml' => $faker->url,
    ];
});
