<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Request;

class RollbarQueryLogEvent extends Event
{
    use SerializesModels;
    public $time;
    public $fullUrl;
    public $pathUrl;

    public function __construct($time)
    {
        $this->time = $time;
        $this->fullUrl = Request::fullUrl();
        $this->pathUrl = Request::getPathInfo();
    }

}
