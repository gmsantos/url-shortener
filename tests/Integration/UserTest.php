<?php

namespace App\Test\Integration;

use App\Test\TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldBeOk()
    {
        $this->assertTrue(true);
    }
}
