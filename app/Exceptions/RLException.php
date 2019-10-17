<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

abstract class RLException extends Exception
{

    public $name;

    public $response;

    public function __construct($message = null)
    {
        parent::__construct($message);
    }

    abstract function getResponse();
}
