<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/**
 *
 * Define constants used globally in the app
 *
 */
define('AWS', 'aws');
define('VULTR', 'vultr');
define('LINODE', 'linode');
define('USER_NAME', 'espectra');
define('DIGITAL_OCEAN', 'digital-ocean');

define('AWS_REGIONS', [
    'us-east-2' => 'Ohio',
    'us-east-1' => 'N. Virginia',
    'us-west-1' => 'N. California',
    'us-west-2' => 'Oregon',
    'ap-east-1' => 'Hong Kong',
    'ap-south-1' => 'Mumbai',
    'ap-northeast-3' => 'Osaka - Local',
    'ap-northeast-2' => 'Seoul',
    'ap-southeast-1' => 'Singapore',
    'ap-southeast-2' => 'Sydney',
    'ap-northeast-1' => 'Tokyo',
    'ca-central-1' => 'Canada',
    'eu-central-1' => 'Frankfurt',
    'eu-west-1' => 'Ireland',
    'eu-west-2' => 'London',
    'eu-west-3' => 'Paris',
    'eu-north-1' => 'Stockholm',
    'sa-east-1' => 'São Paulo'
]);

define('AWS_SIZES', [
    [
        'id' => 't2.nano',
        'name' => '1 vCPUs - 0.5GB RAM (t2.nano)'
    ],
    [
        'id' => 't2.micro',
        'name' => '1 vCPUs - 1GB RAM (t2.micro)'
    ],
    [
        'id' => 't2.small',
        'name' => '1 vCPUs - 2GB RAM (t2.small)'
    ],
    [
        'id' => 't2.medium',
        'name' => '2 vCPUs - 4GB RAM (t2.medium)'
    ],
    [
        'id' => 't2.large',
        'name' => '2 vCPUs - 8GB RAM (t2.large)'
    ],
    [
        'id' => 't2.xlarge',
        'name' => '4 vCPUs - 16GB RAM (t2.xlarge)'
    ],
    [
        'id' => 't2.2xlarge',
        'name' => '8 vCPUs - 32GB RAM (t2.2xlarge)'
    ],
    [
        'id' => 't3.nano',
        'name' => '2 vCPUs - 0.5GB RAM (t3.nano)'
    ],
    [
        'id' => 't3.micro',
        'name' => '2 vCPUs - 1GB RAM (t3.micro)'
    ],
    [
        'id' => 't3.small',
        'name' => '2 vCPUs - 2GB RAM (t3.small)'
    ],
    [
        'id' => 't3.medium',
        'name' => '2 vCPUs - 4GB RAM (t3.medium)'
    ],
    [
        'id' => 't3.large',
        'name' => '2 vCPUs - 8GB RAM (t3.large)'
    ],
    [
        'id' => 't3.xlarge',
        'name' => '4 vCPUs - 16GB RAM (t3.xlarge)'
    ],
    [
        'id' => 't3.2xlarge',
        'name' => '8 vCPUs - 32GB RAM (t3.2xlarge)'
    ],
    [
        'id' => 'm5.large',
        'name' => '2 vCPUs - 8GB RAM (m5.large)'
    ],
    [
        'id' => 'm5.xlarge',
        'name' => '4 vCPUs - 16GB RAM (m5.xlarge)'
    ],
    [
        'id' => 'm5.4xlarge',
        'name' => '16 vCPUs - 64GB RAM (m5.4xlarge)'
    ],
    [
        'id' => 'm5.2xlarge',
        'name' => '8 vCPUs - 32GB RAM (m5.2xlarge)'
    ],
    [
        'id' => 'm5d.large',
        'name' => '2 vCPUs - 8GB RAM (m5d.large)'
    ],
    [
        'id' => 'm5d.xlarge',
        'name' => '4 vCPUs - 16GB RAM (m5d.xlarge)'
    ],
    [
        'id' => 'c5.large',
        'name' => '2 vCPUs - 4GB RAM (c5.large)'
    ],
    [
        'id' => 'c5.xlarge',
        'name' => '4 vCPUs - 8GB RAM (c5.xlarge)'
    ],
    [
        'id' => 'c5.2xlarge',
        'name' => '8 vCPUs - 16GB RAM (c5.2xlarge)'
    ],
    [
        'id' => 'c5.4xlarge',
        'name' => '16 vCPUs - 32GB RAM (c5.4xlarge)'
    ]
]);

define('DIGITAL_OCEAN_REGIONS', [
    [
        'id' => 'nyc1',
        'name' => 'New York 1'
    ],
    [
        'id' => 'sgp1',
        'name' => 'Singapore 1'
    ],
    [
        'id' => 'London 1',
        'name' => 'lon1'
    ],
    [
        'id' => 'New York 3',
        'name' => 'nyc3'
    ],
    [
        'id' => 'Amsterdam 3',
        'name' => 'ams3'
    ],
    [
        'id' => 'Frankfurt 1',
        'name' => 'fra1'
    ],
    [
        'id' => 'Toronto 1',
        'name' => 'tor1'
    ],
    [
        'id' => 'San Francisco 2',
        'name' => 'sfo2'
    ],
    [
        'id' => 'Bangalore 1',
        'name' => 'blr1'
    ]
]);

define('DIGITAL_OCEAN_SIZES', [
    [
        'id' => '201',
        'name' => '1GB RAM - 1 CPU Core - 25GB SSD'
    ],
    [
        'id' => '202',
        'name' => '1GB RAM - 1 CPU Core - 55GB SSD'
    ],
    [
        'id' => '203',
        'name' => '4GB RAM - 2 CPU Core - 80GB SSD'
    ],
    [
        'id' => '204',
        'name' => '8GB RAM - 4 CPU Core -160 GB SSD'
    ],
    [
        'id' => '205',
        'name' => '16GB RAM - 6 CPU Core - 320 GB SSD'
    ],
    [
        'id' => '206',
        'name' => '32GB RAM - 8 CPU Core - 640 GB SSD'
    ],
    [
        'id' => '207',
        'name' => '64GB RAM - 16 CPU Core - 1280 GB SSD'
    ],
    [
        'id' => '208',
        'name' => '96GB RAM - 24 CPU Core - 1600 GB SSD'
    ],
    [
        'id' => '116',
        'name' => '16GB RAM - 4 CPU Core - 2x110 GB SSD'
    ],
    [
        'id' => '117',
        'name' => '24GB RAM - 6 CPU Core - 3x110 GB SSD'
    ],
    [
        'id' => '118',
        'name' => '32GB RAM - 8 CPU Core - 4x110 GB SSD'
    ]
]);

define('LINODE_REGIONS', [
    [
        'id' => 'ca-central',
        'name' => 'Toronto'
    ],
    [
        'id' => 'us-central',
        'name' => 'Dallas'
    ],
    [
        'id' => 'us-west',
        'name' => 'Fremont'
    ],
    [
        'id' => 'us-southeast',
        'name' => 'Atlanta'
    ],
    [
        'id' => 'us-east',
        'name' => 'Newark'
    ],
    [
        'id' => 'eu-west',
        'name' => 'London'
    ],
    [
        'id' => 'eu-central',
        'name' => 'Frankfurt'
    ],
    [
        'id' => 'ap-northeast',
        'name' => 'Tokyo 2'
    ],
    [
        'id' => 'ap-south',
        'name' => 'Singapore'
    ]
]);

define('LINODE_SIZES', [
    [
        'id' => 'g6-nanode-1',
        'name' => '1GB RAM - 1 CPU Cores - 25GB SSD'
    ],
    [
        'id' => 'g6-standard-1',
        'name' => '2GB RAM - 1 CPU Cores - 50GB SSD'
    ],
    [
        'id' => 'g6-standard-2',
        'name' => '4GB RAM - 2 CPU Cores - 80GB SSD'
    ],
    [
        'id' => 'g6-standard-4',
        'name' => '8GB RAM - 4 CPU Cores - 160GB SSD'
    ],
    [
        'id' => 'g6-standard-6',
        'name' => '16GB RAM - 6 CPU Cores - 320GB SSD'
    ],
    [
        'id' => 'g6-standard-8',
        'name' => '32GB RAM - 8 CPU Cores - 640GB SSD'
    ],
    [
        'id' => 'g6-standard-16',
        'name' => '64GB RAM - 16 CPU Cores - 1280GB SSD'
    ],
    [
        'id' => 'g6-standard-20',
        'name' => '96GB RAM - 20 CPU Cores - 1920GB SSD'
    ],
    [
        'id' => 'g6-highmem-1',
        'name' => '24GB RAM (High Memory) - 1 CPU Core - 20GB SSD'
    ],
    [
        'id' => 'g6-highmem-2',
        'name' => '48GB RAM (High Memory) - 2 CPU Cores - 40GB SSD'
    ],
    [
        'id' => 'g6-highmem-4',
        'name' => '90GB RAM (High Memory) - 4 CPU Cores - 90GB SSD'
    ],
    [
        'id' => 'g6-highmem-8',
        'name' => '150GB RAM (High Memory) - 8 CPU Cores - 200GB SSD'
    ],
    [
        'id' => 'g6-highmem-16',
        'name' => '300GB RAM (High Memory) - 16 CPU Cores - 340GB SSD'
    ],
    [
        'id' => 'g6-dedicated-2',
        'name' => '4GB RAM - 2 CPU Cores (Dedicated CPU) - 25GB SSD'
    ],
    [
        'id' => 'g6-dedicated-4',
        'name' => '8GB RAM - 4 CPU Cores (Dedicated CPU) - 50GB SSD'
    ],
    [
        'id' => 'g6-dedicated-8',
        'name' => '16GB RAM - 8 CPU Cores (Dedicated CPU) - 100GB SSD'
    ],
    [
        'id' => 'g6-dedicated-16',
        'name' => '32GB RAM - 16 CPU Cores (Dedicated CPU) - 200GB SSD'
    ],
    [
        'id' => 'g6-dedicated-32',
        'name' => '64GB RAM - 32 CPU Cores (Dedicated CPU) - 400GB SSD'
    ],
    [
        'id' => 'g6-dedicated-48',
        'name' => '96GB RAM - 48 CPU Cores (Dedicated CPU) - 600GB SS'
    ]
]);

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$response->send();

$kernel->terminate($request, $response);
