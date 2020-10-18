<?php

namespace Tests;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

require __DIR__ . '../../constants.php';

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
