<?php

namespace App\Scope;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Http\ResponseFactory;
use Rollbar\Laravel\RollbarServiceProvider;

class ScopeService
{
    private $token;

    private $role;

    private $token_scopes;

    private $default_scopes = [];

    public $app_scopes = [
        'CRON' => [
            'asl.system_orders.read_write'
        ],
        'V' => [
            'asl.report.read',
            'asl.order.read_write',
            'asl.account.read',
            'asl.courier.read',
            'asl.brand.read',
            'asl.category.read',
            'asl.product.read_write',
            'asl.location.read'
        ]
    ];

    public function read(\Closure $scopes) {
        $data = call_user_func($scopes);

        $this->token = $data['token'];

        $this->role = $data['role'];

        $this->token_scopes = $data['token_scopes'] == null ? [] : $data['token_scopes'];
    }

    public function isAllow($route_scopes) {
        if(sizeof($this->token_scopes) == 0) {
            $this->default_scopes = $this->app_scopes[$this->role];
        }

        # Filter token scopes by user type
        $filtered_scopes = array_intersect($this->app_scopes[$this->role], $this->token_scopes);

        # Merge scopes
        $allowed_token_scopes = $this->default_scopes + $filtered_scopes;

        # Check if scopes is allowed
        $scopes = array_intersect($allowed_token_scopes, $route_scopes);

        return sizeof($scopes) > 0;
    }
}
