 <?php

use Faker\Generator as Faker;
use Modules\Proxy\Entities\Proxy;

$factory->define(Proxy::class, function (Faker $faker) {
    return [
        'alias' => $faker->userName . " proxy",
        'user_id' => null,
        'ip_address' => $faker->ipv4,
        'port' => $faker->numberBetween(80, 65000),
        'username' => $faker->unique()->userName,
        'password' => $faker->password,
        'type' => "SOCKS5",
        'monitor' => $faker->boolean,
        'anonimity_level' => "ELITE",
        'last_alive_at' => \Carbon\Carbon::now()->subMinutes($faker->numberBetween(0,24*60)),
        'last_checked_at' => \Carbon\Carbon::now()->subMinutes($faker->numberBetween(0,24*60))
    ];
});
