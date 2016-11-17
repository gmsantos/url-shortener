<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hits', 'url'
    ];

    /**
     * Return the url Owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Build the relative short url.
     *
     * @return string
     */
    public function getShortUrlAttribute()
    {
        return sprintf('/urls/%s', $this->buildShortUrlSlug($this->id));
    }

    /**
     * This method takes an Url id and cipher to use in short Url.
     *
     * @see http://stackoverflow.com/a/3514622/2099835
     *
     * @param $urlId
     *
     * @return string
     */
    protected function buildShortUrlSlug($urlId)
    {
        return strtr(
            rtrim(base64_encode(pack('i', $urlId)), '='),
            '+/',
            '-_'
        );
    }
}
