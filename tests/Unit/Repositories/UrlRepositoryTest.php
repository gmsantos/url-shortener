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

    public function testShouldReportAllSiteStatistics()
    {
        // Set
        $repository = new UrlRepository();

        factory(Url::class, 50)
            ->make()
            ->each(function ($url) {
                $url->user()->associate(factory(User::class)->make());
                $url->save();
            });

        // Simulate queries with Eloquent collections
        $allResults = Url::all();
        $hits = $allResults->sum('hits');
        $urlCount = $allResults->count();

        $top10 = $allResults->sortByDesc('hits')->take(10);
        $topUrls = [];

        foreach ($top10 as $url) {
            $topUrls[] = $repository->presentUrlStats($url);
        }

        $expected = compact('hits', 'urlCount', 'topUrls');

        // Actions
        $result = $repository->reportStatistics();

        // Assertions
        $this->assertEquals($expected, $result);
    }

    public function testShouldReportStatisticsByUser()
    {
        // Set
        $repository = new UrlRepository();
        $userId = 'some-user';
        $user = factory(User::class)->make(['id' => $userId]);

        factory(Url::class, 50)
            ->make()
            ->each(function ($url) use ($user) {
                $user = (bool) rand(0, 1)
                    ? $user
                    : factory(User::class)->make();

                $url->user()->associate($user);
                $url->save();
            });

        // Simulate queries with Eloquent collections
        $allResults = Url::where(['user_id' => $userId])->get();

        $hits = $allResults->sum('hits');
        $urlCount = $allResults->count();

        $top10 = $allResults->sortByDesc('hits')->take(10);
        $topUrls = [];

        foreach ($top10 as $url) {
            $topUrls[] = $repository->presentUrlStats($url);
        }

        $expected = compact('hits', 'urlCount', 'topUrls');

        // Actions
        $result = $repository->reportStatistics($userId);

        // Assertions
        $this->assertEquals($expected, $result);
    }

    public function testShouldReportFalseIfInvalidUserIsSupplied()
    {
        // Set
        $repository = new UrlRepository();
        $userId = 'some-user';

        // Actions
        $result = $repository->reportStatistics($userId);

        // Assertions
        $this->assertFalse($result);
    }
}
