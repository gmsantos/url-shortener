<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\ElasticsearchHandler;

class Elastic {

    public function __invoke() {


        $hosts = [
            // This is effectively equal to: "https://username:password!#$?*abc@foo.com:9200/elastic"
            [
                'host' => 'c2ccd50046f74134b0857506a991924c.eastus2.azure.elastic-cloud.com',
                'port' => '9243',
                'scheme' => 'https',
                'user' => 'elastic',
                'pass' => 'QeBHT31O5hlPXzKnMOrr6zlQ'
            ]
        ];

        $client = \Elasticsearch\ClientBuilder::create()
            ->setHosts($hosts)
            ->build();

        $options = array(
            'index' => 'elastic_log_pgp'
        );
        $handler = new ElasticsearchHandler($client, $options);
        $log = new Logger('application');
        $log->pushHandler($handler);

        return $log;
    }

}

