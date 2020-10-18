<?php

namespace Bahdcoder\Vultr\Tests;

use Bahdcoder\Vultr\Tests\TestCase;

class AccountTest extends TestCase
{
    /** @test */
    public function can_get_account_information()
    {
        $instance = $this->getVultrInstance();

        $this->dd($instance->account()->info());
    }
}
