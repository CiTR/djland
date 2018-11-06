<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $first_name = $faker->firstName;
    $last_name = $faker->lastName;
    $password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret

    $faculties = array(
        null,
        'Applied Science',
        'Arts',
        'Community Planning',
        'Science',
        'Business',
        'Graduate Studies',
        'History',
        'Journalism',
    );

    $membership_types = App\MembershipType::all()->pluck('id');

    return [
        'first_name'               => $first_name,
        'last_name'                => $last_name,
        'email'                    => $faker->unique()->safeEmail,
        'email_verified_at'        => now(),
        'password'                 => $password, // secret
        'preferred_name'           => "{$faker->title} $first_name $last_name",
        'is_canadian_citizen'      => $faker->boolean(75),
        'address'                  => $faker->streetAddress,
        'city'                     => $faker->city,
        'province'                 => $faker->provinceAbbr,
        'postal_code'              => $faker->postcode,
        'membership_type_id'       => $faker->numberBetween(1,4),
        'is_new'                   => $faker->boolean,
        'is_alumni'                => $faker->boolean,
        'is_approved'              => $faker->boolean(80),
        'is_discorder_contributor' => $faker->boolean,
        'member_since'             => $faker->numberBetween(2014,date("Y")),
        'faculty'                  => $faker->randomElement($faculties),
        'school_year'              => ($faker->boolean(25)) ? null : $faker->numberBetween(1,6),
        'student_no'               => ($faker->boolean(25)) ? null : $faker->randomNumber(8),
        'course_integrate'         => $faker->boolean,
        'primary_phone'            => ($faker->boolean(25)) ? null : $faker->phoneNumber,
        'secondary_phone'          => ($faker->boolean(25)) ? null : $faker->phoneNumber,
        'comments'                 => ($faker->boolean(25)) ? null : $faker->sentence,
        'about'                    => ($faker->boolean(25)) ? null : $faker->sentence,
        'skills'                   => ($faker->boolean(25)) ? null : $faker->sentence,
        'exposure'                 => ($faker->boolean(25)) ? null : $faker->sentence,
        'taken_station_tour'       => $faker->boolean,
        'taken_tech_training'      => $faker->boolean,
        'taken_prog_training'      => $faker->boolean,
        'taken_prod_training'      => $faker->boolean,
        'taken_spoken_training'    => $faker->boolean,
        'remember_token'           => str_random(10),
    ];
});
