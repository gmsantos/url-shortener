<?php

namespace App\Test\Unit\Repositories;

use App\Repositories\UrlRepository;
use App\Url;
use App\Test\TestCase;
use Mockery as m;

class UrlRepositoryTest extends TestCase
{
    public function testShouldRetrieveUrlBySlug()
    {
        // Set
        $slug = 'some-slug';
        $url = m::mock(Url::class);
        $repository = new UrlRepository();

        // Expectations
        $url->shouldReceive('find')
            ->with(1)
            ->andReturnSelf();

        $this->app->instance(Url::class, $url);

        // Actions
        $result = $repository->findBySlug($slug);

        // Assertions
        $this->assertEquals($url, $result);
    }

    public function testShouldRetrieveUrlBySlugAndFail()
    {
        // Set
        $slug = 'some-slug';
        $repository = new UrlRepository();
        $url = m::mock(Url::class);

        // Expectations
        $url->shouldReceive('find')
            ->with(1)
            ->andReturn(false);

        $this->app->instance(Url::class, $url);

        // Actions
        $result = $repository->findBySlug($slug);

        // Assertions
        $this->assertFalse($result);
    }

    public function testShouldCreateANewUrl()
    {
        // Set
        $url = 'http://www.chaordic.com.br/folks';
        $user_id = 'some-user';
        $repository = new UrlRepository();
        $urlModel = m::mock(Url::class);

        // Expectations
        $url->shouldReceive('create')
            ->with(compact('user_id', 'url'))
            ->andReturnSelf();

        $this->app->instance(Url::class, $url);

        // Actions
        $result = $repository->createUrl($url, $user_id);

        // Assertions
        $this->assertEquals($urlModel, $result);
    }

    public function testShouldBuildShortUrl()
    {
        // Set
        $urlId = 1;
        $repository = new UrlRepository();
        $expectedSlug = 'AQAAAA';

        // Actions
        $result = $repository->buildShortUrl($urlId);

        // Assertions
        $this->assertEquals($expectedSlug, $result);
    }

    public function testShouldDecodeShortUrl()
    {
        // Set
        $slug = 'AQAAAA';
        $repository = new UrlRepository();
        $urlId = 1;

        // Actions
        $result = $repository->decodeShortUrl($slug);

        // Assertions
        $this->assertEquals($urlId, $result);
    }
}
