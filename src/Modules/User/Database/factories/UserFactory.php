<?php

use Modules\User\Entities\User;


$factory->define(
    User::class, function (Faker\Generator $faker) {
    return [
        'provider' => 'database',
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});
