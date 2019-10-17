<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use phpDocumentor\Reflection\Types\Parent_;

class RouteException extends RLException
{
    public function __construct($message = null)
    {
        parent::__construct('Method or Route not found');

        $this->name = 'ROUTE_FAILURE';

        $this->code = 404;

        if($message != null) {
            $this->message = $message;
        }
    }

    public function getResponse()
    {
        return Response::json([
            'name' => $this->name,
            'message' => $this->getMessage()
        ], $this->code);
    }
}
