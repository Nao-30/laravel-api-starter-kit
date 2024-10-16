<?php

namespace Tests;

use App\Http\Traits\TestingHelpers;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use TestingHelpers, DatabaseTransactions;
}
