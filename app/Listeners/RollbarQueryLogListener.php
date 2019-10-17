<?php

namespace App\Listeners;

use App\Events\RollbarQueryLogEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class RollbarQueryLogListener
{
    public function handle(RollbarQueryLogEvent $event)
    {
        try {
            $rollbar = app()->make('rollbar.querylog');
            $time = $event->time;
            $queries = DB::getQueryLog();
            $message = array(
                'endpoint'    => "$event->fullUrl",
                'time'        => $time." ms",
                'access_date' => date('Y-m-d H:i:s'),
                'detail'      => $queries
            );

            $title = "endpoint : $event->pathUrl";
            $extra_data = array(
                'title' => $title
            );
            $log = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if($time >= 200 and $time <= 500) {
                $level = 'Warning';
            } elseif($time > 500) {
                $level = 'Critical';
            }

            $rollbar->report_message($log,$level,$extra_data);
        } catch(Exception $e) {
            $log = ['Listener' => 'RollbarQueryLogListener', 'function' => 'handle'];
            logError($e, $log);
        }
    }
}
