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
$factory->define(App\Neo::class, function (Faker\Generator $faker) {

    return [
        'reference_id' => $faker->numberBetween(1000000, 9999999),
        'name' => $faker->name,
        'speed'=> $faker->randomFloat(3, 1000),
        'is_hazardous' => $faker->randomElement(array(0, 1)),
        'date' => $faker->date(),
    ];

});