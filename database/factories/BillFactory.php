<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bill;
use Faker\Generator as Faker;

$factory->define(Bill::class, function (Faker $faker) {
    $provision = rand(0, 1);
    return [
        'date' => $faker->date(),
        'deadline' => $provision ? $faker->date() : null,
        'tax_stamp' => 0.6,
        'provider_id' => function () {
            return \App\Provider::inRandomOrder()->first()->id;
        },
        'provision' => $provision
    ];
});