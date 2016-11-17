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

        $url = new Url();
        $url->url = $longUrl;
        $url->user()->associate($user);
        $url->save();

        return $url;
    }
}