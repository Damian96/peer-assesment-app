<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\Session;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Session::class, function (Faker $faker) {
    return [
        'course_id' => Course::all()->random()->id,
        'title' => $faker->text(50),
        'instructions' => $faker->text(50),
        'deadline' => Carbon::now(config('app.timezone'))->addWeeks(rand(1, 2))->timestamp,
        'open_date' => Carbon::now(config('app.timezone'))->addDays(rand(1, 2))->timestamp,
        'groups' => 2,
        'min_group_size' => 2,
        'max_group_size' => 3,
    ];
});
