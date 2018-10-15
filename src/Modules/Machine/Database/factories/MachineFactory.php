<?php

use Faker\Generator as Faker;

$factory->define(Modules\Machine\Entities\Machine::class, function (Faker $faker) {
    $os = [
        'MAC',
        'WINDOWS',
        'LINUX',
    ];

    return [
        'name'             => 'Machine '.$username = $faker->userName,
        'hostname'         => 'DESKTOP-'.$pcname = strtoupper(preg_replace('/[^a-zA-Z0-9]+/', '', base64_encode(random_bytes(5)))),
        'username'         => $username,
        'os'               => get_random_array_element($os),
        'hash'             => hash('sha256', $username.'\t'.$faker->macAddress.'\t'.$pcname, false),
        'active'           => $faker->boolean,
        'ip_address'       => $faker->ipv4,
        'mac_address'      => $faker->macAddress,
        'memory_usage'     => rand(512, 8192),
        'memory_available' => 8192,
        'cpu_usage'        => rand(0, 100),
    ];
});
