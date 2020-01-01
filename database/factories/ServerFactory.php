<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Server;
use Faker\Generator as Faker;

$factory->define(Server::class, function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'user_id' => factory(App\User::class),
        'type' => $faker->randomElement([
            'load_balancer',
            'database',
            'default'
        ]),
        'provider' => $faker->randomElement([
            DIGITAL_OCEAN,
            LINODE,
            CUSTOM_PROVIDER,
            VULTR
        ]),
        'size' => '512mb',
        'region' => 'nyc1',
        'ssh_key' => $faker->realText(16),
        'mysql_root_password' => $faker->word(),
        'mysql8_root_password' => $faker->word(),
        'mariadb_root_password' => $faker->word(),
        'mongodb_admin_password' => $faker->word(),
        'postgres_root_password' => $faker->word()
    ];
});
