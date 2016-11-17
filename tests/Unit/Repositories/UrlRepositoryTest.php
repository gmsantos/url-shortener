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
}
