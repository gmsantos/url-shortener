<?php

namespace App\Test\Unit;

use App\Url;
use App\Test\TestCase;

class UrlTest extends TestCase
{
    public function testShouldBuildShortUrl()
    {
        // Set
        $url = new Url();
        $url->id = 1;
        $expectedUrl = '/urls/AQAAAA';

        // Assertions
        $this->assertEquals($expectedUrl, $url->shortUrl);
    }
}