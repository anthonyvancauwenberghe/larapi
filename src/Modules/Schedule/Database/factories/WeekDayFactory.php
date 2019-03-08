<?php

use Faker\Generator as Faker;
use Modules\Schedule\Entities\WeekDay;

$factory->define(WeekDay::class, function (Faker $faker) {

        for ($h = 0; $h < 24; $h++) {
            $times[$h] = $faker->boolean;
        }

        $inInterval = false;
        $interval = [];
        $intervals = [];
    for ($h = 0; $h < 24; $h++) {
       // if(sizeof($interval)
    }
return [];
});


