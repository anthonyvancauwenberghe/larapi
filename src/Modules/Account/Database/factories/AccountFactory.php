 <?php

use Faker\Generator as Faker;
 use Modules\Account\Entities\Account;

 $factory->define(Account::class, function (Faker $faker) {

     return [
         'user_id' => 1,
         'username' => $faker->unique()->safeEmail,
         'password' => $faker->password,
         'game'     => 'OSRS',
         'banned_at' => $faker->boolean(80) ? null : \Carbon\Carbon::now()->subHours($faker->numberBetween(1, 500)),
         'last_heartbeat_at' => $faker->boolean(50) ? null : \Carbon\Carbon::now()->subSeconds($faker->numberBetween(1, 500)),
         'performance_mode' => array_random([
             'EXTREME',
             'MEDIUM',
             'DISABLED'
         ]),
         'logged_in' => $faker->boolean,
         'machine_id' => 1
     ];
 });

 $factory->state(Account::class, 'OSRS', function (Faker $faker) {
     return [
         'game' => 'OSRS',
         'bank_pin' => $faker->randomNumber(4, true),
         'ingame_name' => $faker->userName,
         'membership_expires_at' => $faker->boolean(80) ? null : \Carbon\Carbon::now()->addHours($faker->numberBetween(1, 500)),
         "location" => [
             "x" => $faker->numberBetween(-10000, 10000),
             "y" => $faker->numberBetween(-10000, 10000),
             "z" => $faker->numberBetween(0, 3)
         ],
         "skills" => [
             "hitpoints" => $faker->numberBetween(0, 200000000),
             "attack" => $faker->numberBetween(0, 200000000),
             "strength" => $faker->numberBetween(0, 200000000),
             "defence" => $faker->numberBetween(0, 200000000),
             "ranged" => $faker->numberBetween(0, 200000000),
             "prayer" => $faker->numberBetween(0, 200000000),
             "magic" => $faker->numberBetween(0, 200000000),
             "runecrafting" => $faker->numberBetween(0, 200000000),
             "crafting" => $faker->numberBetween(0, 200000000),
             "mining" => $faker->numberBetween(0, 200000000),
             "smithing" => $faker->numberBetween(0, 200000000),
             "fishing" => $faker->numberBetween(0, 200000000),
             "cooking" => $faker->numberBetween(0, 200000000),
             "firemaking" => $faker->numberBetween(0, 200000000),
             "woodcutting" => $faker->numberBetween(0, 200000000),
             "agility" => $faker->numberBetween(0, 200000000),
             "herblore" => $faker->numberBetween(0, 200000000),
             "thieving" => $faker->numberBetween(0, 200000000),
             "fletching" => $faker->numberBetween(0, 200000000),
             "slayer" => $faker->numberBetween(0, 200000000),
             "farming" => $faker->numberBetween(0, 200000000),
             "construction" => $faker->numberBetween(0, 200000000),
             "hunter" => $faker->numberBetween(0, 200000000),
         ],
         "items" => [
             "bank" => [
                 [
                     "item_id" => 5,
                     "amount" => 2
                 ],
                 [
                     "item_id" => 4,
                     "amount" => 3
                 ],
                 [
                     "item_id" => 2,
                     "amount" => 1
                 ]
             ],
             "equipment" => [],
             "grand_exchange" => [
                 [
                     "item_id" => 5,
                     "amount" => 2
                 ]
             ],
             "inventory" => []
         ]
     ];
 });

 $factory->state(Account::class, 'RS3', function (Faker $faker) {
     return [
         'game' => 'RS3',
         'bank_pin' => $faker->randomNumber(4, true),
         'ingame_name' => $faker->userName,
         'membership_expires_at' => null,
     ];
 });
