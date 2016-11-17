<?php

namespace App\Test\Unit\Repositories;

use App\Repositories\UrlRepository;
use App\Test\TestCase;
use App\Url;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UrlRepositoryTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testShouldRetrieveUrlBySlug()
    {
        // Set
        $slug = 'AQAAAA';
        $url = 'http://www.chaordic.com.br/folks';
        $user_id = 'some-user';
        $repository = new UrlRepository();

        factory(User::class)->create(['id' => $user_id]);
        factory(Url::class)->create(compact('url', 'user_id'));

        // Actions
        $result = $repository->findBySlug($slug);

        // Assertions
        $this->assertInstanceOf(Url::class, $result);
        $this->seeInDatabase('urls', $result->attributesToArray());
    }

    public function testShouldRetrieveUrlBySlugAndFail()
    {
        // Set
        $slug = 'some-slug';
        $repository = new UrlRepository();

        // Actions
        $result = $repository->findBySlug($slug);

        // Assertions
        $this->assertNull($result);
    }

    public function testShouldCreateANewUrl()
    {
        // Set
        $url = 'http://www.chaordic.com.br/folks';
        $user_id = 'some-user';
        $repository = new UrlRepository();

        factory(User::class)->create(['id' => $user_id]);

        // Actions
        $result = $repository->createUrl($url, $user_id);

        // Assertions
        $this->assertInstanceOf(Url::class, $result);
        $this->seeInDatabase('urls', $result->attributesToArray());
    }

    public function testPresentAnUrlStats()
    {
        // Set
        $url = new Url();
        $url->id = 1;
        $url->hits = 15;
        $url->url = 'http://www.chaordic.com.br/folks';

        $repository = new UrlRepository();

        $expected = [
            'id' => '1',
            'hits' => 15,
            'url' => 'http://www.chaordic.com.br/folks',
            'shortUrl' => 'http://localhost/urls/AQAAAA'
        ];

        // Actions
        $result = $repository->presentUrlStats($url);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
