<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\PaymentMethod::class, function (Faker $faker) {
    return [
        'primary' => 1,
        'pay_method_type' => \App\PaymentMethodType::TYPE_T1_PAYMENTS,
        'token' => $faker->creditCardNumber,
        'cvv' => mt_rand(100, 999),
        'expMonth' => 12,
        'expYear' => 2099,
        'firstname' => 'Johnny',
        'lastname' => 'Buum',
        'created_at' => now(),
        'updated_at' => now()
    ];
});
