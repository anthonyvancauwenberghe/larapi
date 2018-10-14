<?php

use Modules\User\Entities\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'provider' => 'database',
        'identity_id' => (new \MongoDB\BSON\ObjectId())->__toString(),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});
