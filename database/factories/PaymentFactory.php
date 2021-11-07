<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    if (array_rand([0, 1]))
        return [
            'check_number' => array_rand([false, true]) ? $faker->unique()->numberBetween(1000000, 9999999) : null,
            'date' => $faker->date()
        ];
    return [];
});