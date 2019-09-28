<?php

use Faker\Generator as Faker;
use Jenhacool\Repository\Tests\Fixtures\TestModel;

$factory->define(TestModel::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'gender' => $faker->numberBetween(1, 2)
    ];
});

$factory->state(TestModel::class, 'male', [
    'gender' => 1
]);

$factory->state(TestModel::class, 'female', [
    'gender' => 2
]);
