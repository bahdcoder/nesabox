<?php

namespace Bahdcoder\DigitalOcean\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Get a mock client
     *
     * @return Client
     */
    public function getMockClient()
    {
        $handler = new MockHandler([new Response(200, [])]);

        return new Client([
            'handler' => $handler
        ]);
    }

    /**
     * Die var dump helper test function.
     *
     * @return void
     */
    public function dd($variable)
    {
        die(var_dump($variable));
    }
}
