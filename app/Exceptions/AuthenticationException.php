<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use phpDocumentor\Reflection\Types\Parent_;

class AuthenticationException extends RLException
{
    public function __construct($message = null)
    {
        parent::__construct('Authentication failed due to invalid authentication credentials or a missing Authorization header.');

        $this->name = 'AUTHENTICATION_FAILURE';

        $this->code = 401;

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
