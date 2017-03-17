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
        'followers_count'     => $faker->numberBetween(0, 2000000),
        'access_token'        => str_random(10),
        'access_token_secret' => str_random(10),
    ];
});

$factory->define(App\Follower::class, function (Faker\Generator $faker) {
    return [
        'id'        => $faker->randomNumber(8),
        'name'      => $faker->name,
        'username'  => $faker->userName,
        'avatar'    => "http://fillmurray.com/200/200",
        'followers' => $faker->numberBetween(0, 2000000),
        'affection' => $faker->numberBetween(0, 6000000),
    ];
});

$factory->define(App\Photo::class, function (Faker\Generator $faker) {
    return [
        'id'          => $faker->randomNumber(8),
        'name'        => $faker->name,
        'description' => $faker->realText(),
        'privacy'     => $faker->boolean(95),
        'link'        => $faker->url,
        'url'         => "http://fillmurray.com/250/250",
        'url_full'    => "http://fillmurray.com/1280/800",
        'posted_at'   => $faker->dateTimeBetween('-2 year', 'now'),
        'user_id'     => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'id'          => $faker->randomNumber(8),
        'body'        => $faker->realText(),
        'posted_at'   => $faker->dateTimeBetween('-1 year', 'now'),
        'read'        => $faker->boolean(70),
        'follower_id' => function () {
            return factory(App\Follower::class)->create()->id;
        },
        'photo_id'    => function () {
            return factory(App\Photo::class)->create()->id;
        },
    ];
});