<?php

namespace App\Repositories;

use App\Url;
use App\User;

/**
 * This class is responsible to handle Url logic, like build
 * stats reports and cipher Url Ids.
 */
class UrlRepository
{
    /**
     * Try to find an Url by an encoded slug.
     *
     * @param $urlSlug
     *
     * @return Url|null
     */
    public function findBySlug($urlSlug)
    {
        $id = $this->decodeShortUrl($urlSlug);

        return Url::find($id);
    }

    /**
     * Create a new Url for a specific user.
     *
     * @param $longUrl
     * @param $userId
     *
     * @return Url|bool
     */
    public function createUrl($longUrl, $userId)
    {
        if (is_null($user = User::find($userId))) {
            return false;
        }

        $url = new Url([
            'url' => $longUrl
        ]);

        $url->user()->associate($user);
        $url->save();

        return $url;
    }

    /**
     * Report site statistics.
     *
     * @return bool
     */
    public function reportStatistics()
    {
        $hits = (int) Url::sum('hits');
        $urlCount = Url::count();
        $topUrlsResult = Url::orderBy('hits', 'desc')
            ->limit(10)
            ->get();

        $topUrls = [];

        foreach ($topUrlsResult as $url) {
            $topUrls[] = $this->presentUrlStats($url);
        }

        return compact('hits', 'urlCount', 'topUrls');
    }

    /**
     * Report user statistics.
     *
     * @param string $userId
     *
     * @return bool
     */
    public function reportStatisticsByUser($userId)
    {
        if (is_null(User::find($userId))){
            return false;
        }

        $hits = (int) Url::where(['user_id' => $userId])->sum('hits');
        $urlCount = Url::where(['user_id' => $userId])->count();
        $topUrlsResult = Url::where(['user_id' => $userId])->orderBy('hits', 'desc')
            ->limit(10)
            ->get();

        $topUrls = [];

        foreach ($topUrlsResult as $url) {
            $topUrls[] = $this->presentUrlStats($url);
        }

        return compact('hits', 'urlCount', 'topUrls');
    }

    /**
     * Present an Url stats
     *
     * @param Url $url
     *
     * @return array
     */
    public function presentUrlStats(Url $url)
    {
        return [
            'id' => (string) $url->id,
            'hits' => (int) $url->hits,
            'url' => $url->url,
            'shortUrl' => $url->shortUrl,
        ];
    }

    /**
     * Decode a slug to a simple database Url id.
     *
     * @see http://stackoverflow.com/a/3514622/2099835
     *
     * @param $slug
     *
     * @return mixed
     */
    protected function decodeShortUrl($slug)
    {
        $id = unpack(
            'i',
            base64_decode(
                str_pad(strtr($slug, '-_', '+/'), strlen($slug) % 4, '=')
            )
        );

        return $id[1];
    }
}