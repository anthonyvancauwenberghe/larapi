<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\ScriptExclusivity;

$factory->define(ScriptExclusivity::class, function (Faker $faker) {
    return [
        ScriptExclusivity::BASE_PRICE => $faker->randomFloat(2,0,50),
        ScriptExclusivity::RECURRING_PRICE => $faker->randomFloat(2,0,10),
    ];
});
