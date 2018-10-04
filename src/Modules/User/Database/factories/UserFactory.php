<?php

use Modules\User\Entities\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'auth0_id' => null,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'api_token' => bin2hex(random_bytes(64))
    ];
});
