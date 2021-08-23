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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->unique()->userName,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'distid' => $faker->numerify('TSA#####'),
        'usertype' => \App\User::USER_TYPE_DISTRIBUTOR,
        'account_status' => \App\User::ACC_STATUS_APPROVED,
        'language' => $faker->languageCode,
        'country_code' => $faker->countryCode,
        'is_tax_confirmed' => 1,
        'estimated_balance' => $faker->randomNumber(3),
        'current_product_id' => $faker->randomElement([1,2,3,4]),
        'subscription_product' => $faker->randomElement([11,12,26,33]),
        'is_sites_deactivate' => 0,
        'created_dt' => now(),
        'created_date' => now()->format('Y-m-d'),
        'created_time' => now()->Format('H:i:s')
    ];
});
