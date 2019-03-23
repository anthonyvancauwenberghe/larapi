<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\ScriptRelease;
use Modules\Script\Support\Version;

$factory->define(ScriptRelease::class, function (Faker $faker) {
    return [
        ScriptRelease::TYPE => $faker->randomElement(['MINOR','MAJOR','PATCH']),
        ScriptRelease::CHANGELOG => "A Script Release"
    ];
});
