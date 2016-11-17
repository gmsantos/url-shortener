<?php

namespace App\Jobs;

use App\Url;

class UrlWasHit extends Job
{
    /**
     * @var Url
     */
    private $url;

    /**
     * Create a new job instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hits = $this->url->hits;

        $this->url->hits = ++$hits;

        $this->url->save();
    }
}
