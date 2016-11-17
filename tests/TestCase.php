<?php

namespace App\Test;

use Laravel\Lumen\Testing\TestCase as LumenTestCase;

class TestCase extends LumenTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        // Fix missing Url on tests
        $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME'])
            ? $_SERVER['SERVER_NAME']
            : 'localhost';

        $_SERVER['SERVER_PORT'] = isset($_SERVER['SERVER_PORT'])
            ? $_SERVER['SERVER_PORT']
            : '80';

        return require __DIR__.'/../bootstrap/app.php';
    }
}
