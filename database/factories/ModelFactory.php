<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define('App\Models\Subscription', function (Faker $faker) {
    return [
        'plan_code'     => $faker->unique()->randomElement(),
        'name'          => $faker->text(50),
        'monthly_cost'  => $faker->numberBetween(1, 100),
        'annual_cost'   => $faker->numberBetween(1, 100),
        'flag'          => 1,
    ];
});
