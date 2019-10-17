<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    public $baseUrl = 'http://apiseller.ralali.local';

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    function setUp()
    {
        parent::setUp();

        $this->withoutMiddleware();
    }
}
