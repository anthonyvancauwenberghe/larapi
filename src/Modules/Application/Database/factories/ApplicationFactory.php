<?php

use Faker\Generator as Faker;
use Modules\Application\Entities\Application;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'alias' => null,
        'user_id' => 1,
        'machine_id' => null,
        'proxy_id' => null,
        'schedule_id' => null,
        'performance_mode' => array_random([
            'EXTREME',
            'MEDIUM',
            'DISABLED',
        ]),
        'banned_at' => $faker->boolean(80) ? null : \Carbon\Carbon::now()->subHours($faker->numberBetween(1, 500)),
    ];
});

$factory->state(Application::class, 'OSRS', function (Faker $faker) {
    return [
        'type' => 'OSRS',
        'credentials' => [
            'username' => $faker->unique()->safeEmail,
            'password' => $faker->password,
            'bank_pin' => $faker->randomNumber(4, true),
        ],
        'script_id' => null,
        'script_config_id' => null,
    ];
});
