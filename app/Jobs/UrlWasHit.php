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
    public function handle(\Psr\Log\LoggerInterface $logger)
    {
        $logger->info('URL hit', ['url' => $this->url->shortUrl]);

        $this->url->increment('hits');

        $this->url->update();
    }
}
