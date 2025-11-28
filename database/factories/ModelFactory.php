<?php


$factory->define(App\UserAccount::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'password' => bcrypt('123456'),
        'role' => 'customer',
        'api_token' => str_random(60),
    ];
});