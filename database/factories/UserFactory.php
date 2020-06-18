<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    $fname = $faker->firstName;
    $lname = $faker->lastName;
    $username = strtolower(substr($fname, 0, 1) . $lname);
    return [
        'fname' => $fname,
        'lname' => $lname,
        'email' => sprintf("%s@%s", $username, config('env.ORG_DOMAIN')),
        'email_verified_at' => now(),
        'password' => Hash::make('123'), // password
        'remember_token' => Str::random(10),
        'api_token' => hash('sha256', Str::random(80)),
    ];
});
