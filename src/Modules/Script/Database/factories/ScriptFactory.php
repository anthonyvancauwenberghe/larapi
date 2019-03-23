<?php

use Faker\Generator as Faker;
use Modules\Script\Entities\Script;
use Modules\Script\Support\RsaGenerator;

$factory->define(Script::class, function (Faker $faker) {
    $rsa = RsaGenerator::generateKeyPair();
    return [
        Script::AUTHOR_ID => null,
        Script::NAME => $faker->text(),
        Script::DESCRIPTION => $faker->text(),
        Script::SHORT_DESCRIPTION => $faker->text(),
        Script::GAME => 'OSRS',
        Script::PUBLIC => $faker->boolean,
        Script::BASE_PRICE => $faker->randomFloat(2,0,50),
        Script::RECURRING_PRICE => $faker->randomFloat(2,0,10),
        Script::GIT_ACCESS => $faker->boolean,
        Script::REPOSITORY_URL => $faker->text(),
        Script::PUBLIC_KEY => $rsa->getPublicKey(),
        Script::PRIVATE_KEY => $rsa->getPrivateKey(),
        Script::TAGS => []
    ];
});
