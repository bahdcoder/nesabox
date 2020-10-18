<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['active', 'deleted']),
        'next_bill_date' => '2020/04/11',
        'subscription_plan_id' => $faker->randomElement(
            config('paddle.plans')->all()
        ),
        'subscription_id' => $faker->randomNumber(),
        'user_id' => factory(App\User::class)
    ];
});
