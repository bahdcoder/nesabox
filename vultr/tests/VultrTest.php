<?php

namespace Bahdcoder\Vultr\Tests;

use Bahdcoder\Vultr\Vultr;
use Bahdcoder\Vultr\Api\Account;

class VultrTest extends TestCase
{
    /** @test */
    public function exposes_the_account_api()
    {
        $instance = new Vultr('fake_api_key');

        $this->assertInstanceOf(Account::class, $instance->account());
    }
}
