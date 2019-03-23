<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\ScriptReviewReply;

$factory->define(ScriptReviewReply::class, function (Faker $faker) {
    return [
        ScriptReviewReply::REPLIER_ID => null,
        ScriptReviewReply::MESSAGE => $faker->text
    ];
});
