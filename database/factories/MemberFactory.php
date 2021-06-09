<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;
use Modules\Member\Model\Member;

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

$factory->define(Member::class, function(Faker $faker){
    return [
        'name'              => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'phone'             => $faker->unique()->phoneNumber,
        'email_verified_at' => now(),
        'username'          => $faker->userName,
        'password'          => '$2y$10$Pji4MA/QQD1GTRPpRs2IAOTGfQUelsUGzYlWMDM.iZKHxqSMXRlUu', // password
        'remember_token'    => Str::random(10),
        'type_id'           => 1
    ];
});
