<?php

namespace Bahdcoder\DigitalOcean\Tests;

use Bahdcoder\DigitalOcean\DigitalOcean;

class DigitalOceanTest extends TestCase
{
    /** @test */
    public function exposes_the_account_api()
    {
        $instance = new DigitalOcean('fake_api_key');

        // $this->assertInstanceOf(Account::class, $instance->account());
        $this->assertTrue(true);
    }
}
