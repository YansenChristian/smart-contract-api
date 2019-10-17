<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class RollbarServiceProvider extends ServiceProvider
{

    protected $listen = [
        'App\Events\RollbarQueryLogEvent' => [
            'App\Listeners\RollbarQueryLogListener',
        ],
    ];

    public function register(){
        $this->app->singleton('rollbar.querylog', function () {
            $rollbar = new \Rollbar\Rollbar();
            $rollbar->init( array(
                'access_token' => env('ROLLBAR_TOKEN_QUERY_LOG'),
                'environment' => $this->app->environment(),
                'handler' => 'agent',
                'agent_log_location' => env('ROLLBAR_AGENT_LOG_PATH')
            ));
            
            return $rollbar;
        });
    }
}
