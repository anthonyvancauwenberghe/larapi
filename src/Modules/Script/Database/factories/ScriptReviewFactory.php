<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\ScriptReview;

$factory->define(ScriptReview::class, function (Faker $faker) {
    return [
        ScriptReview::REVIEWER_ID => null,
        ScriptReview::MESSAGE => $faker->text(),
        ScriptReview::RATING => $faker->numberBetween(1.5),
    ];
});
