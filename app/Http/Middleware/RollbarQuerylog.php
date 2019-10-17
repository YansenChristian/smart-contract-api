<?php

namespace App\Http\Middleware;
use App\Events\RollbarQueryLogEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Closure;

class RollbarQuerylog
{
    public function handle($request, Closure $next)
    {
        $time_start= microtime(true);
        DB::enableQueryLog();
        $response = $next($request);   
        $time_exec = round((microtime(true) - $time_start) * 1000);
        
        if ( $time_exec > 200 && is_dir(env('ROLLBAR_AGENT_PATH')) && is_dir(env('ROLLBAR_AGENT_LOG_PATH')) ) {
            app()->register('App\Providers\RollbarServiceProvider');
            Event::fire(new RollbarQueryLogEvent($time_exec));
        }
        
        return $response;
    }
}
