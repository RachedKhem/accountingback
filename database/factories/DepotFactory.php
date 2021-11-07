<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Depot;
use Faker\Generator as Faker;

$factory->define(Depot::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(1, 1000),
        'type' => $faker->numberBetween(0, 1),
        'date' => $faker->date()
    ];
});
