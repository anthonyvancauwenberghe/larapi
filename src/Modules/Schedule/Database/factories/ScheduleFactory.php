 <?php

use Faker\Generator as Faker;
use Modules\Schedule\Entities\Schedule;
 use Modules\Schedule\Entities\WeekDay;

 $factory->define(Schedule::class, function (Faker $faker) {

    $times = [];
    for ($d = 0; $d < 7; $d++) {
        for ($h = 0; $h < 24; $h++) {
            $times[$d][$h] = $faker->boolean;
        }
    }

    return [
        Schedule::USER_ID => null,
        Schedule::ALIAS => $faker->userName . " schedule",
        Schedule::TIMEZONE => \Carbon\Carbon::now()->timezone->getName(),
        'times' => $times,
        Schedule::WEEK_DAYS => [
            WeekDay::MONDAY => WeekDay::fromFactory()->raw()
        ],
        Schedule::EXCEPTIONS => [],
        Schedule::RANDOMIZE => $faker->boolean
    ];
});
