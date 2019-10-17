<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ScopeException extends RLException
{

    public function __construct()
    {
        parent::__construct(null);

        $this->code = 403;
    }

    public function getResponse()
    {
        return Response::create(null, $this->code);
    }
}
