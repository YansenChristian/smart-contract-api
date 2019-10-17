<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthenticationException;
use App\Exceptions\ScopeException;
use App\Scope\ScopeService;
use Closure;
use Firebase\JWT\ExpiredException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Cache;

class Scope
{
    public function handle($request, Closure $next, ...$scopes)
    {
        $service = app()->get('scope.service');
        if($service->isAllow($scopes) == false) {
            throw new ScopeException();
        }

        return $next($request);
    }
}
