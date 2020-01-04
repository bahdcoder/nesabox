<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Team;
use App\TeamInvite;
use App\User;
use Faker\Generator as Faker;

$factory->define(TeamInvite::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'team_id' => factory(Team::class),
        'user_id' => factory(User::class),
        'status' => $faker->randomElement(['accepted', 'invited', 'declined'])
    ];
});
