<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id'                  => $faker->randomNumber(8),
        'name'                => $faker->name,
        'email'               => $faker->unique()->safeEmail,
        'remember_token'      => str_random(10),
        'username'            => $faker->userName,
        'avatar'              => "http://fillmurray.com/200/200",
        'access_token'        => str_random(10),
        'access_token_secret' => str_random(10),
    ];
});
