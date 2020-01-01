<?php

namespace Tests;


use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

require __DIR__ . '../../constants.php';

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
