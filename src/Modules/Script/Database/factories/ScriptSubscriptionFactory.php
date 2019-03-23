<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\ScriptSubscription;

$factory->define(ScriptSubscription::class, function (Faker $faker) {
    return [
        ScriptSubscription::USER_ID => null,
        ScriptSubscription::ACTIVE => $faker->boolean,
        ScriptSubscription::BASE_PRICE => $faker->randomFloat(2, 0,50),
        ScriptSubscription::RECURRING_PRICE => $faker->randomFloat(2, 0,50)
    ];
});
